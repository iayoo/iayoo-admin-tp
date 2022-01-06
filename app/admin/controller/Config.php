<?php
/**
 *
 */


namespace app\admin\controller;


class Config extends BaseController
{
    public function index()
    {
        return $this->fetch('');
    }
}