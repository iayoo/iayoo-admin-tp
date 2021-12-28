<?php
/**
 *
 */


namespace app\services;


use app\model\AdministratorPermission;

class AdministratorPermissionService extends BaseService
{
    protected $model = AdministratorPermission::class;

    public function getAll(){
        return $this->model::order('sort','asc')->select()->toArray();
    }
}