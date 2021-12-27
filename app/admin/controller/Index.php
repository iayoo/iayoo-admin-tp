<?php
/**
 *
 */


namespace app\admin\controller;


class Index extends BaseController
{
    public function index(){
//        return "Admin Home Index Page";
        return $this->fetch('');
    }
}