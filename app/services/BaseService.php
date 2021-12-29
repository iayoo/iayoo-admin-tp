<?php
/**
 *
 */


namespace app\services;


use Iayoo\ServiceHelper\thinkphp\ServiceProvider;
use think\exception\ValidateException;

class BaseService extends ServiceProvider
{

    protected $isLayui = false;

    /**
     * @param bool $isLayui
     * @return BaseService
     */
    public function setIsLayui(bool $isLayui)
    {
        $this->isLayui = $isLayui;
        return $this;
    }

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
        $model = $this->model::where($where)->find();
        return $model?$model->toArray():null;
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

    public function save($data){
        if (isset($data['id']) && !empty($data['id'])){
            $id = $data['id'];
            return $this->update(['id'=>$id],$data);
        }
        return $this->create($data);
    }

    public function getLayuiTableList(){

    }



    public function searchList($where){
        $data = $this->model::where($where)->page($this->page,$this->limit)->select();
        $total = $this->model::where($where)->count();
        if ($this->isLayui){
            return [
                'data'=>$data,
                'count'=>$total
            ];
        }
        return compact('data','total');
    }
}