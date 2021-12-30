<?php
/**
 *
 */


namespace app\services;


use app\model\AdministratorRole;
use app\model\AdministratorRolePermission;

class AdministratorRoleService extends BaseService
{
    protected $model = AdministratorRole::class;

    // 获取用户直接权限
    public function getPermission($id)
    {
        $role = $this->model::with('permissions')->find($id);
        /** @var AdministratorPermissionService $permissionService */
        $permissionService = app()->make(AdministratorPermissionService::class);
        $permissions       = $permissionService->getList();
        foreach ($permissions as $permission) {
            foreach ($role->permissions as $v) {
                if ($permission->id == $v['id']) {
                    $permission->own = true;
                }
            }
        }
        $permissions = ToolService::getTree($permissions);
        return ['role' => $role, 'permissions' => $permissions];
    }

    public function savePermission($id, $data)
    {
        if ($data) {
            //清除原有的直接权限
            AdministratorRolePermission::where('role_id', $id)->delete();
            //填充新的直接权限
            foreach ($data as $p) {
                AdministratorRolePermission::insert([
                    'role_id'       => $id,
                    'permission_id' => $p,
                ]);
            }

        }
        return true;
    }
}