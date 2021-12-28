<?php
/**
 *
 */


namespace app\services;


use Iayoo\ServiceHelper\thinkphp\ServiceProvider;

class BaseService extends ServiceProvider
{
    public function update($where,$data){
        return $this->model::where($where)->update($data);
    }

    public function create($data){
        return $this->model::create($data);
    }

    public function get($data){
        return $this->model::where($data)->find();
    }
}