<?php
declare (strict_types = 1);

namespace app\admin\validate;

use app\services\AdministratorService;
use think\Validate;

class Administrator extends Validate
{
    protected $failException = true;

    /**
     * 定义验证规则
     * 格式：'字段名' =>  ['规则1','规则2'...]
     *
     * @var array
     */
    protected $rule = [
        'username'  => 'require',
        'password'  => 'require|matching',
    ];

    /**
     * 定义错误信息
     * 格式：'字段名.规则名' =>  '错误信息'
     *
     * @var array
     */
    protected $message = [
        'username.require'  => '用户名不能为空',
        'password.require'  => '密码不能为空',
        'password.matching' => '用户名密码错误',
    ];

    protected $scene = [
        'login'=>['username','password']
    ];

    protected function matching($value, $rule, $data=[]){
        /** @var AdministratorService $service */
        $service = app()->make(AdministratorService::class);
        if ($service->matching($data['username'],$value)){
            return true;
        }
        return false;
    }
}
