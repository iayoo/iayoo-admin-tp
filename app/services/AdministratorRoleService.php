<?php
/**
 *
 */


namespace app\services;


use app\model\AdministratorRole;

class AdministratorRoleService extends BaseService
{
    protected string $model = AdministratorRole::class;

    public function searchList($where)
    {
        $queryWhere = [];
        return parent::searchList($queryWhere);
    }

    // 获取用户直接权限
    public function getPermission($id)
    {
        $role = $this->model::with('permissions')->find($id);
        /** @var AdministratorPermissionService $permissionService */
        $permissionService = app()->make(AdministratorPermissionService::class);
        $permissions = $permissionService->getAll();
        foreach ($permissions as $permission){
            foreach ($role->permissions as $v){
                if ($permission->id == $v['id']){
                    $permission->own = true;
                }
            }
        }
        $permissions = ToolService::getTree($permissions);
        return ['role'=>$role,'permissions'=>$permissions];
    }
}