<?php


namespace app\admin\controller;


use app\services\AdministratorService;
use think\Request;

class Login extends BaseController
{
    //后台登录
    public function index(){
        if ($this->request->isAjax()){
            /** @var AdministratorService $service */
            $service = app()->make(AdministratorService::class);
            if ($service->login($this->request->param())){
                return $this->json("登录成功");
            }
            return $this->json("登录失败");
//            $this->json(S::login(Request::param()));
        }
        return $this->fetch();
    }
}