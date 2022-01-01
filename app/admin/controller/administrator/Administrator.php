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
    /**
     * @var AdministratorService
     */
    protected $service = AdministratorService::class;

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

    public function role(AdministratorService $service)
    {
        return $this->fetch('',$service->getRole($this->request->param('id')));
    }

    public function updateRole(AdministratorService $service){
        if ($service->updateRole($this->request->param('id'),$this->request->param('roles'))){
            return $this->success("操作成功");
        }
        return $this->error("操作失败");
    }

    public function permission(AdministratorService $service)
    {
        if ($this->request->isAjax()){
            if ($service->savePermission($this->request->param('id'),$this->request->param('permissions'))){
                return $this->success("操作成功");
            }
            return $this->error("操作失败");
        }
        return $this->fetch('',$service->getPermission($this->request->param('id')));
    }

    public function removeLog(AdministratorLogService $administratorLogService)
    {
        if ($administratorLogService->clear()){
            return $this->success("操作成功");
        }
        return $this->error("操作失败");
    }

}