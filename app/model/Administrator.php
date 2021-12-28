<?php
declare (strict_types = 1);

namespace app\model;

use think\Model;

/**
 * @mixin \think\Model
 */
class Administrator extends Model
{
    //
    public function roles()
    {
        return $this->belongsToMany(AdministratorRole::class, AdministratorAdministratorRole::class, 'role_id', 'admin_id');
    }

    // 管理的直接权限
    public function directPermissions()
    {
        return $this->belongsToMany(AdministratorPermission::class, AdministratorAdministratorPermission::class, 'permission_id', 'admin_id');
    }

}
