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
        dump($service->parserToken('eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiIsImZvbyI6ImJhciJ9.eyJpc3MiOiJodHRwOi8vZXhhbXBsZS5jb20iLCJhdWQiOiJodHRwOi8vZXhhbXBsZS5vcmciLCJqdGkiOiI0ZjFnMjNhMTJhYSIsImlhdCI6MTY0MTI5MjUyNi4yNjY5OTEsIm5iZiI6MTY0MTI5MjU4Ni4yNjY5OTEsImV4cCI6MTY0MTI5NjEyNi4yNjY5OTEsInVpZCI6MX0.MUUV6fBnv-Jywe_Qa73rD0-GeRSrFp7e0CD0i4IiUkg'));
    }
}
