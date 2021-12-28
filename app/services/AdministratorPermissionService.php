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

    // 获取列表
    public function getList()
    {
        return $this->model::order('id','desc')->select();
    }
}