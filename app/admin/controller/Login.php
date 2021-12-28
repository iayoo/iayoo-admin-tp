<?php


namespace app\admin\controller;


use app\services\AdministratorService;
use app\validate\admin\Administrator as AdministratorValidate;
use think\Request;

class Login extends BaseController
{

    /**
     * 后台登录
     * @param AdministratorValidate $validate
     * @return string|void
     * @throws \Exception
     */
    public function index(AdministratorValidate $validate){
        if ($this->request->isAjax()){
            $validate->scene('login')->check($this->request->param());
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