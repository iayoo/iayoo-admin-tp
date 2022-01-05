<?php
/**
 *
 */


namespace app\api\controller;


use app\BaseController;
use Iayoo\ApiResponse\Response\ThinkPHP\ResponseTrait;

class ApiBaseController extends BaseController
{
    use ResponseTrait;

    protected function getParams(){
        return $this->request->param();
    }
}