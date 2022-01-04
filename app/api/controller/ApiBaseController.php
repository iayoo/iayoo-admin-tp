<?php
/**
 *
 */


namespace app\api\controller;


use app\BaseController;

class ApiBaseController extends BaseController
{
    protected function getParams(){
        return $this->request->param();
    }
}