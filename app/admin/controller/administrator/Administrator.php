<?php
/**
 *
 */


namespace app\admin\controller\administrator;


use app\admin\controller\BaseController;
use app\services\AdministratorLogService;
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
        $this->assign('admin',[]);
        return $this->fetch();
    }

    public function save(AdministratorService $service){
        if ($service->save($this->request->param())){
            return $this->success("保存成功");
        }else{
            return $this->error("保存失败");
        }
    }

    public function edit($id,AdministratorService $service){
        $adminInfo = $service->get($id);
        $this->assign('admin',$adminInfo);
        return $this->fetch('add');
    }

    public function batchRemove(AdministratorService $service){
        if ($service->goBatchRemove($this->request->param('ids'))){
            return $this->success("删除成功");
        }
        return $this->error("删除失败");
    }

    public function log(){
        return $this->fetch();
    }

    public function logList(AdministratorLogService $administratorLogService)
    {
        return $this->setIsLayer(true)
            ->success(
                $administratorLogService->setIsLayui(true)->searchList($this->request->param()
                ));
    }
}