<?php
/**
 *
 */


namespace app\admin\controller;


use think\facade\View;

class BaseController extends \app\BaseController
{
    /**
     * 解析和获取模板内容 用于输出
     * @param string $template
     * @param array $vars
     * @return string
     */
    protected function fetch(string $template = '', array $vars = []){
        return View::fetch( $template,$vars);
    }

    /**
     * 模版变量赋值
     * @param $name
     * @param null $value
     * @return \think\View
     */
    protected function assign($name,$value = null){
        return View::assign($name,$value);
    }
}