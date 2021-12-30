<?php


namespace app\admin\middleware;


use app\services\AdministratorLogService;
use app\services\AdministratorService;
use think\facade\Request;
use think\Session;

class AdminBehaviorLogMiddleware
{
    public function handle($request, $next)
    {
        /** @var AdministratorLogService $service */
        $service = app()->make(AdministratorLogService::class);
        /** @var AdministratorService $adminService */
        $adminService = app()->make(AdministratorService::class);
        $isLog = true;
        $desc = $request->except(['s','_pjax','ajax'])??[];
        if(isset($desc['page'])&&isset($desc['limit']))return $next($request);;
        foreach ($desc as $k => $v) {
            if(stripos($k, 'fresh') !== false){
                return $next($request);
            }
            if (is_string($v) && strlen($v) > 255 || stripos($k, 'password') !== false)  {
                unset($desc[$k]);
            }
        }

        $info = [
            'uid'        => $adminService->getId()??0,
            'url'        => $request->url(),
            'desc'       => json_encode($desc),
            'ip'         => $request->ip(),
            'user_agent' => $request->server('HTTP_USER_AGENT')
        ];
        $service->save($info);
        return $next($request);
    }
}