<?php
/**
 *
 */


namespace app\admin\controller;


use app\services\AdministratorService;
use app\services\ToolService;
use think\facade\Session;

class Index extends BaseController
{
    public function index(){
        return $this->fetch('');
    }

    // 欢迎页
    public function home(){
        return $this->fetch('',$this->getSystem());
    }

    /**
     * 菜单列表
     * @param AdministratorService $service
     */
    public function menu(AdministratorService $service){
        return $this->success(ToolService::getTree($service->permissions($service->getId(),request()->root())));
    }
}