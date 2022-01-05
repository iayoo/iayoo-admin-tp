<?php
/**
 *
 */


namespace app\api\controller;
use app\services\JWTAuthService;
use app\services\user\UserService;
use think\exception\ValidateException;

class User extends ApiBaseController
{
    public function login(JWTAuthService $service,UserService $userService)
    {
        $params = $this->getParams();
        $user = $userService->get(['account'=>$params['account']]);
        if (!$user){
            throw new ValidateException("用户不存在");
        }
        $token = $service->token(
            [
                'id'       => $user['id'],
                'nickname' => $user['nickname']
            ]);
        if (!$token){
            throw new ValidateException("用户不存在");
        }
        return $this->success('登录成功',[
            'access_token' => $token,
            'id'           => $user['id'],
            'nickname'     => $user['nickname']
        ]);
    }

    public function check(JWTAuthService $service)
    {
        dump($service->getAuthData());
    }
}
