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

    public function add(){
        return $this->fetch();
    }

    public function save(AdministratorService $service){
        if ($service->save($this->request->param())){
            return $this->success("创建成功");
        }else{
            return $this->error("创建失败");
        }
    }
}