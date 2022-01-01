<?php


namespace app\services;


use app\model\AdministratorLog;

class AdministratorLogService extends BaseService
{
    protected $model = AdministratorLog::class;

    public function clear(){
        return $this->model::where('id','>',0)->delete(true);
    }

}