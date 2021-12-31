<?php


namespace app\admin\middleware;


use app\services\AdministratorService;
use Closure;
use Iayoo\ApiResponse\Response\ThinkPHP\ResponseTrait;
use think\facade\Route;
use think\facade\Session;
use think\Request;
use think\Response;
use think\route\Url;

class AuthMiddleware
{
    use ResponseTrait;
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
        $admin = Session::get('admin');
        if (!$admin && $request->url() !== $loginPath){
            if ($request->isAjax()){
                return $this->error('未登录',40100);
            }else{
                return redirect($loginPath);
            }
        }
        if ($request->url() !== $loginPath){

            /** @var AdministratorService $service */
            $service = app()->make(AdministratorService::class);
            $service->setId($admin['id']);
        }
        return $next($request);
    }
}