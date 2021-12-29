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

    public function add(){
        return $this->fetch();
    }

    public function edit($id, AdministratorRoleService $service)
    {
        return $this->fetch('add',$service->get($id));
    }

    public function save(AdministratorRoleService $service){
        if ($service->save($this->request->param())){
            return $this->success('操作成功');
        }
        return $this->error('操作失败');
    }

    public function remove(AdministratorRoleService $service){
        if ($service->remove($this->request->param('id'))){
            return $this->success("操作成功");
        }
        return $this->error('操作失败');
    }

    public function savePermission(AdministratorRoleService $service)
    {
        if ($service->savePermission($this->request->param('id'),$this->request->param('permissions'))){
            return $this->success('操作成功');
        }
        return $this->error('操作失败');

    }

    public function permission(AdministratorRoleService $service){
        return $this->fetch('',$service->getPermission($this->request->param('id')));
    }
}