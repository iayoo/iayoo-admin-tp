<?php
/**
 *
 */


namespace app\admin\controller;


use app\services\ToolService;
use think\facade\Session;

class Index extends BaseController
{
    public function index(){
//        return "Admin Home Index Page";
        return $this->fetch('');
    }

    // 欢迎页
    public function home(){
        return $this->fetch('',$this->getSystem());
    }

    /**
     * 菜单列表
     */
    public function menu(){
        return $this->success(ToolService::getTree(Session::get('admin.menu')));
    }
}