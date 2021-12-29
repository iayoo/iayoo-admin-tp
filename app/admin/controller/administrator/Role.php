<?php
/**
 *
 */


namespace app\admin\controller\administrator;


use app\admin\controller\BaseController;
use app\services\AdministratorRoleService;

class Role extends BaseController
{
    public function index()
    {
        return $this->fetch();
    }

    /**
     * @param AdministratorRoleService $service
     */
    public function getList(AdministratorRoleService $service){
        $result = $service->setIsLayui(true)->searchList($this->request->param());
        return $this->setIsLayer(true)->success($result);
    }
}