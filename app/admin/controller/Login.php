<?php


namespace app\admin\controller;


use think\Request;

class Login extends BaseController
{
    //后台登录
    public function index(){
        if ($this->request->isAjax()){
//            $this->json(S::login(Request::param()));
        }
        return $this->fetch();
    }
}