<?php
/**
 *
 */


namespace app\services;


use app\model\AdministratorAdministratorPermission;
use app\model\AdministratorAdministratorRole;
use app\model\AdministratorPermission;
use app\model\AdministratorRolePermission;

class AdministratorPermissionService extends BaseService
{
    protected $model = AdministratorPermission::class;

    public function getAll(){
        return $this->model::order('sort','asc')->select()->toArray();
    }

    // 获取列表
    public function getList()
    {
        return $this->model::order('id','desc')->select();
    }

    public function remove($id,$type = 1){
        $model = $this->model::with('child')->find($id);
        if($type){
            $arr = AdministratorPermission::where('pid',$id)->field('id,pid')->select();
            foreach($arr as $k=>$v){
                AdministratorPermission::where('pid',$v['id'])->delete();
                AdministratorAdministratorPermission::where('permission_id',$v['id'])->delete();
                AdministratorRolePermission::where('permission_id',$v['id'])->delete();
            }
        }else{
            if (isset($model->child) && !$model->child->isEmpty()){
//                throw new
                return ['msg'=>'存在子权限，确认删除后不可恢复','code'=>201];
            }
        }
        $model->delete();
        AdministratorRolePermission::where('permission_id', $id)->delete();
        AdministratorAdministratorPermission::where('permission_id', $id)->delete();
    }
}