<?php
/**
 *
 */


namespace app\admin\controller;


use think\exception\HttpResponseException;
use think\facade\View;
use think\Response;

class BaseController extends \app\BaseController
{
    /**
     * 解析和获取模板内容 用于输出
     * @param string $template
     * @param array $vars
     * @return string
     */
    protected function fetch(string $template = '', array $vars = []){
        return View::fetch( $template,$vars);
    }

    /**
     * 模版变量赋值
     * @param $name
     * @param null $value
     * @return \think\View
     */
    protected function assign($name,$value = null){
        return View::assign($name,$value);
    }

    // 获取系统参数
    protected function getSystem(){
        return [
            'os' => PHP_OS,
            'space' => round((disk_free_space('.')/(1024*1024)),2).'M',
            'addr' =>$_SERVER['HTTP_HOST'],
            'run' => $this->request->server('SERVER_SOFTWARE'),
            'php' => PHP_VERSION,
            'php_run' => php_sapi_name(),
            'mysql' => function_exists('mysql_get_server_info')?mysql_get_server_info():\think\facade\Db::query('SELECT VERSION() as mysql_version')[0]['mysql_version'],
            'think' => $this->app->version(),
            'upload' => ini_get('upload_max_filesize'),
            'max' => ini_get('max_execution_time').'秒',
            'ver' => 'V5.0.1',
        ];
    }

    /**
     * 格式化返回Json数据
     * @param string|null $msg
     * @param int $code
     * @param array $data
     * @param array $extend
     * @param int $httpCode
     * @return void
     */
    public function json (string $msg = null,int $code = 200,array $data = [],$extend = [],int $httpCode = 200)
    {
        $result = [
            'msg' => $msg,
            'code'  => $code,
            'time' => time()
        ];
        if (!empty($data)) {
            $result['data'] = $data;
        }
        if (!empty($extend)) {
            foreach ($extend as $k => $v) {
                $result[$k] = $v;
            }
        }
        $response = Response::create($result, 'json', $httpCode);
        throw new HttpResponseException($response);
    }
}