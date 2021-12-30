<?php


namespace app\admin\controller;


use app\services\AdministratorService;
use app\validate\admin\Administrator as AdministratorValidate;
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
            $administratorService = app()->make(AdministratorService::class);
            if ($administratorService->login($this->request->param())){
                return $this->success("登录成功");
            }
            return $this->success("登录失败");
        }
        return $this->fetch();
    }

    public function logout(AdministratorService $administratorService){
        $administratorService->logout();
        return $this->success("退出成功");
    }
}