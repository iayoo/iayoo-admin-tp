<?php
/**
 *
 */


namespace app\admin\controller\administrator;


use app\services\AdministratorPermissionService;

class Permission extends \app\admin\controller\BaseController
{
    public function index(AdministratorPermissionService $service){
        if ($this->request->isAjax()){
            $list = $service->getList();
            return $this->setIsLayer(true)->success(['code'=>0,'data'=>$list->toArray(),'extend'=>['count' => $list->count()]]);
        }
        return $this->fetch();
    }

    public function getList(){
        return $this->success();
    }
}