<?php
/**
 *
 */


namespace app\services;


use app\model\Administrator;
use app\model\AdministratorAdministratorPermission;
use app\model\AdministratorAdministratorRole;
use app\model\AdministratorPermission;
use app\model\AdministratorRole;
use app\Request;
use think\db\exception\DbException;
use think\exception\ValidateException;
use think\facade\Cookie;
use think\facade\Db;
use think\facade\Session;

class AdministratorService extends BaseService
{
    protected $model = Administrator::class;

    protected $id;

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id): void
    {
        $this->id = $id;
    }

    public function passwordEncode($password){
        return password_hash($password,PASSWORD_BCRYPT);
    }

    // 用户登录验证
    public function login(array $data)
    {
        //验证用户
        $admin = $this->model::where([
            'username' => trim($data['username']),
            'status' => 1
        ])->find();
        if(!$admin) throw new \Exception('用户名密码错误');
        $admin->token = ToolService::rand_string().$admin->id.microtime(true);
        $admin->save();
        //是否记住密码
        $time = 3600;
        if (isset($data['remember'])) $time = 30 * 86400;
        //缓存登录信息
        $info = [
            'id' => $admin->id,
            'token' => $admin->token,
            'menu' => $this->permissions($admin->id,\request()->root())
        ];
        Session::set('admin', $info);
        Cookie::set('token',$admin->token, $time);
        // 触发登录成功事件
        event('AdminLog');
        return true;
    }

    // 退出登陆
    public function logout()
    {
        Session::delete('admin');
        Cookie::delete('token');
        Cookie::delete('sign');
        return true;
    }

    /**
     * 用户的所有权限
     * @param $id
     * @param $root
     * @return array
     */
    public function permissions($id,$root)
    {
        /** @var AdministratorPermissionService $administratorPermissionService */
        $administratorPermissionService = app()->make(AdministratorPermissionService::class);

        $admin = $this->model::with(['roles.permissions', 'directPermissions'])->findOrEmpty($id)->toArray();
        $permissions = [];
        //超级管理员缓存所有权限
        if ($admin['id'] == 1){
            $perms = $administratorPermissionService->getAll();
            foreach ($perms as $p){
                if($p['status'] == 1){
                    $permissions[$p['id']] =  $p;
                    $permissions[$p['id']]['href'] = ToolService::is_url($p['href'])??$root.$p['href'];
                }
            }
            if(env('APP_DEBUG')==true){
                $permissions[0] = [
                    "id" => -1,
                    "pid" => 0,
                    "title" => "自动生成",
                    "icon" => "layui-icon layui-icon-util",
                    "type" => 0,
                    "href" => "",
                ];
                $permissions[-1] = [
                    "id" => -2,
                    "pid" => -1,
                    "title" => "CRUD管理",
                    "icon" => "layui-icon layui-icon-console",
                    "type" => 1,
                    "openType" => "_iframe",
                    'href'=> $root."/crud/index",
                ];
            }
        }else{
            //处理角色权限
            if (isset($admin['roles']) && !empty($admin['roles'])) {
                foreach ($admin['roles'] as $r) {
                    if (isset($r['permissions']) && !empty($r['permissions'])) {
                        foreach ($r['permissions'] as $p) {
                            if($p['status'] == 1){
                                $permissions[$p['id']] =  $p;
                                $permissions[$p['id']]['href'] = ToolService::is_url($p['href'])??$root.$p['href'];
                            }
                        }
                    }
                }
            }
            //处理直接权限
            if (isset($admin['directPermissions']) && !empty($admin['directPermissions'])) {
                foreach ($admin['directPermissions'] as $p) {
                    if($p['status'] == 1){
                        $permissions[$p['id']] =  $p;
                        $permissions[$p['id']]['href'] = ToolService::is_url($p['href'])??$root.$p['href'];
                    }
                }
            }
            $key = array_column($permissions, 'sort');
            array_multisort($key,SORT_ASC,$permissions);
        }
        //合并权限为用户的最终权限
        return $permissions;
    }

    // 获取列表
    public function searchList($search = null)
    {
        $where = [];
        if ($search) {
            $where[] = ['username', 'like', "%" . $search . "%"];
        }
        $list = $this->model::order('id','desc')
            ->where('id','<>',$this->getId())
            ->where('id','>','1')
            ->withoutField('password,token,delete_time')
            ->where($where)
            ->paginate($this->limit);
        return ['data'=>$list->items(),'extend'=>['count' => $list->total(), 'limit' => $this->limit]];
    }

    public function matching($username,$password){
        $adminInfo = $this->get([
            'username' => trim($username),
            'status'   => 1
        ]);
        if (!$adminInfo){
            return false;
        }
        return password_verify($password,$adminInfo['password']);
    }

    public function save($data){
        if (isset($data['password'])){
            if (empty($data['password'])){
                unset($data['password']);
            }else{
                $data['password'] = $this->passwordEncode($data['password']);
            }
        }
        if (isset($data['username'])){
            $data['username'] = trim($data['username']);
        }
        if (isset($data['id']) && !empty($data['id'])){
            $id = $data['id'];
            return $this->update(['id'=>$id],$data);
        }
        if ($this->get(['username'=>$data['username']])){
            throw new ValidateException("账号已存在");
        }
        $data['token'] = '';
        return $this->create($data);
    }

    public function getRole($id){
        $admin = $this->model::with('roles')->where('id',$id)->find();
        $roles = AdministratorRole::select();
        foreach ($roles as $k=>$role){
            if (isset($admin->roles) && !$admin->roles->isEmpty()){
                foreach ($admin->roles as $v){
                    if ($role['id']==$v['id']){
                        $roles[$k]['own'] = true;
                    }
                }
            }
        }
        return ['admin'=>$admin,'roles'=>$roles];
    }
    // 用户分配角色
    public function updateRole($id,$data)
    {
        if($data){
            Db::startTrans();
            try{
                //清除原先的角色
                AdministratorAdministratorRole::where('admin_id',$id)->delete();
                //添加新的角色
                foreach ($data as $v){
                    AdministratorAdministratorRole::create([
                        'admin_id' => $id,
                        'role_id' => $v,
                    ]);
                }
                Db::commit();
            }catch (\Exception $e){
                Db::rollback();
                return false;
            }
        }
        return true;
    }

    public function getPermission($id)
    {
        $admin = $this->model::with('directPermissions')->find($id);
        $permissions = AdministratorPermission::order('sort','asc')->select();
        foreach ($permissions as $permission){
            foreach ($admin->direct_permissions as $v){
                if ($permission->id == $v['id']){
                    $permission->own = true;
                }
            }
        }
        $permissions = ToolService::getTree($permissions->toArray());
        return ['admin'=>$admin,'permissions'=>$permissions];
    }

    public function savePermission($id,$data)
    {
        if($data){
            Db::startTrans();
            try{
                //清除原有的直接权限
                AdministratorAdministratorPermission::where('admin_id',$id)->delete();
                //填充新的直接权限
                foreach ($data as $v){
                    AdministratorAdministratorPermission::create([
                        'admin_id' => $id,
                        'permission_id' => $v,
                    ]);
                }
                Db::commit();
            }catch (DbException $exception){
                Db::rollback();
                return false;
            }
        }
        return true;
    }

    public function updatePassword($id,$password)
    {
        return $this->save(['id'=>$id,'password'=>$this->passwordEncode($password)]);
    }
}