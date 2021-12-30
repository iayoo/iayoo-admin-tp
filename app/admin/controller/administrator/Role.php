<?php
/**
 *
 */


namespace app\admin\controller\administrator;


use app\admin\controller\BaseController;
use app\services\AdministratorRoleService;

class Role extends BaseController
{
    /** @var AdministratorRoleService  */
    protected $service = AdministratorRoleService::class;

    public function savePermission()
    {
        if ($this->service->savePermission($this->request->param('id'),$this->request->param('permissions'))){
            return $this->success('操作成功');
        }
        return $this->error('操作失败');
    }

    public function permission(){
        return $this->fetch('',$this->service->getPermission($this->request->param('id')));
    }
}