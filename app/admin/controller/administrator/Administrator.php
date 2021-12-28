<?php
/**
 *
 */


namespace app\admin\controller\administrator;


use app\admin\controller\BaseController;
use app\services\AdministratorService;

class Administrator extends BaseController
{
    public function index(AdministratorService $service){
        if ($this->request->isAjax()){
            return $this->setIsLayer(true)->success($service->getList());
        }
        return $this->fetch();
    }
}