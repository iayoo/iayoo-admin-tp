<?php
declare (strict_types = 1);

namespace app\middleware;

use app\services\JWTAuthService;

class JWTAuthMiddleware
{
    /**
     * 处理请求
     *
     * @param \think\Request $request
     * @param \Closure       $next
     * @return Response
     */
    public function handle($request, \Closure $next)
    {
        /** @var $jwtService JWTAuthService */
        $jwtService = app(JWTAuthService::class);
        if (!$jwtService->parserRequestHeaderToken($request->header('authorization'))->auth()){
            throw new \OAuthException();
        }
        return $next($request);
    }
}
