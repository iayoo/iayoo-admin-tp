<?php


namespace app\admin\controller;


use app\services\AdministratorService;
use think\Request;

class Login extends BaseController
{
    /**
     * 后台登录
     * @return string|void
     * @throws \Exception
     */
    public function index(){
        if ($this->request->isAjax()){
            /** @var AdministratorService $service */
            $service = app()->make(AdministratorService::class);
            if ($service->login($this->request->param())){
                return $this->success("登录成功");
            }
            return $this->success("登录失败");
        }
        return $this->fetch();
    }
}