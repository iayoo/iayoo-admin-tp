<?php
/**
 *
 */


namespace app\services;


use app\model\AdministratorRole;

class AdministratorRoleService extends BaseService
{
    protected string $model = AdministratorRole::class;

    public function searchList($where)
    {
        $queryWhere = [];
        return parent::searchList($queryWhere);
    }
}