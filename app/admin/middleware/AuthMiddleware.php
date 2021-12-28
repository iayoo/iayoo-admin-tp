<?php


namespace app\admin\middleware;


use app\services\AdministratorService;
use Closure;
use think\facade\Route;
use think\facade\Session;
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
        $admin = Session::get('admin');
        /** @var AdministratorService $service */
        $service = app()->make(AdministratorService::class);
        $service->setId($admin['id']);
        return $next($request);
    }
}