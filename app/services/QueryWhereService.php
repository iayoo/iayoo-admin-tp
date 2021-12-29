<?php
/**
 *
 */


namespace app\services;


class QueryWhereService
{
    protected array $where = [];

    public function __call($name, $arguments)
    {
//        call_user_func([],$this,$arguments);
    }
}