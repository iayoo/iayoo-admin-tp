<?php
declare (strict_types = 1);

namespace app\model;

use think\Model;

/**
 * @mixin \think\Model
 */
class AdministratorRole extends Model
{
    // 角色所有的权限
    public function permissions()
    {
        return $this->belongsToMany(
            AdministratorPermission::class,
            AdministratorRolePermission::class,
            'permission_id',
            'role_id'
        );
    }
}
