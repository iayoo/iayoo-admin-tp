<?php
/**
 *
 */


namespace app\api\controller;
use app\services\JWTAuthService;

class User extends ApiBaseController
{
    public function login(JWTAuthService $service)
    {
        dump($this->getParams());
        dump($service->token());
    }

    public function check(JWTAuthService $service)
    {
        dump($service
            ->setToken('eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiIsImZvbyI6ImJhciJ9.eyJpc3MiOiJodHRwOi8vZXhhbXBsZS5jb20iLCJhdWQiOiJodHRwOi8vZXhhbXBsZS5vcmciLCJqdGkiOiI0ZjFnMjNhMTJhYSIsImlhdCI6MTY0MTM2MTI2MywibmJmIjoxNjQxMzYxMzIzLCJleHAiOjE2NDEzNjQ4NjMsInVpZCI6MX0.RHEsCgWPRSTqVpPC9UQTm6xvxMmS5vZBnigkIpfJBcg')
            ->auth()
        );
    }
}
