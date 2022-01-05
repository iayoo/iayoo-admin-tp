<?php
/**
 *
 */


namespace app\services\user;


use app\model\user\User;
use app\services\BaseService;

class UserService extends BaseService
{
    protected $model = User::class;
}