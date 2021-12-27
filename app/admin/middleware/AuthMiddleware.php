<?php


namespace app\admin\middleware;


use Closure;
use think\facade\Route;
use think\Request;
use think\Response;
use think\route\Url;

class AuthMiddleware
{
    /**
     * 检查session
     * @access public
     * @param Request $request
     * @param Closure $next
     * @return Response
     */
    public function handle($request, Closure $next)
    {
        $loginPath = Route::buildUrl('/admin/login')->suffix(null)->build();
        if (!$request->session('admin') && $request->url() !== $loginPath){
            return redirect($loginPath);
        }
        return $next($request);
    }
}