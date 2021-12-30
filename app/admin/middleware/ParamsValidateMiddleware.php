<?php
/**
 *
 */


namespace app\admin\middleware;


use think\exception\ValidateException;

class ParamsValidateMiddleware
{
    public function handle($request, $next)
    {
        //获取当前参数
        $params = $request->param();
        //获取访问模块
        $module = str_replace("/","",$request->root());
        //获取访问控制器
        $controller = ucfirst($request->controller());
        //获取操作名,用于验证场景scene
        $scene    = $request->action();
        $validate = "app\\" . $module . "\\validate\\" . $controller;
        //仅当验证器存在时 进行校验
        if (class_exists($validate) && $request->isAjax()) {
            $v = new $validate();
            if ($v->hasScene($scene)) {
                //仅当存在验证场景才校验
                $result = $v->scene($scene)->check($params);
                if (true !== $result) {
                    //校验不通过则抛出异常，留后面自定义异常获取
                    throw new ValidateException($result);
                }
            }
        }
        return $next($request);

    }
}