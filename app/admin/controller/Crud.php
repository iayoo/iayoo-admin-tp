<?php


namespace app\admin\controller;


use app\services\CrudService;

class Crud extends BaseController
{
    public function index()
    {
        if ($this->request->isAjax()) {
            return $this->setIsLayer(true)->success(CrudService::getTable());
        }
        return $this->fetch('',[
            'prefix' => config('database.connections.mysql.prefix')
        ]);
    }

    public function list()
    {
        return $this->setIsLayer(true)->success(CrudService::getTableFields($this->request->param('name')));
    }

    public function add()
    {
        if ($this->request->isAjax()) {
            if (CrudService::addTable($this->request->param())){
                return $this->success('操作成功');
            }
            return $this->error('操作失败');
        }
        return $this->fetch('',[
            'prefix' => config('database.connections.mysql.prefix')
        ]);
    }

    // 删除
    public function remove(){
        if (CrudService::removeTable($this->request->param('name'))){
            return $this->success('操作成功');
        }
        return $this->error('操作失败');
    }

    public function crud()
    {
        return $this->fetch('',CrudService::getCrud($this->request->param('name')));
    }
}