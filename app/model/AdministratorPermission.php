<?php
declare (strict_types = 1);

namespace app\model;

use think\Model;

/**
 * @mixin \think\Model
 */
class AdministratorPermission extends Model
{
    //
    // 子权限
    public function child()
    {
        return $this->hasMany(AdministratorPermission::class,'pid','id');
    }
}
