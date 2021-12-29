<?php
/**
 *
 */


namespace app\services;


use Iayoo\ServiceHelper\thinkphp\ServiceProvider;
use think\exception\ValidateException;

class BaseService extends ServiceProvider
{
    public function update($where,$data){
        return $this->model::where($where)->update($data);
    }

    public function create($data){
        return $this->model::create($data);
    }

    public function get($data){
        $where = [];
        if (is_numeric($data)){
            $where['id'] = $data;
        }
        if (is_array($data)){
            $where = $data;
        }
        return $this->model::where($where)->find();
    }

    /**
     * 软删除
     * @param $ids
     * @return bool
     * @throws \Exception
     */
    public function goBatchRemove($ids)
    {
        if (!is_array($ids)) throw new ValidateException("数据不存在");
        try{
            return $this->model::destroy($ids);
        }catch (\Exception $e){
            throw new \Exception('操作失败'.$e->getMessage());
        }
    }
}