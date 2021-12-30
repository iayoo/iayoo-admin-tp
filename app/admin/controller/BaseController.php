<?php
/**
 *
 */


namespace app\admin\controller;


use app\services\AdministratorService;
use Iayoo\ApiResponse\Response\ThinkPHP\ResponseTrait;
use think\App;
use think\exception\HttpResponseException;
use think\facade\View;
use think\Response;

class BaseController extends \app\BaseController
{
    use ResponseTrait;

    protected $service;

    public function __construct(App $app)
    {
        parent::__construct($app);
        if ($this->service){
            $this->service = app()->make($this->service);
        }
    }

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

    public function index(){
        if ($this->request->isAjax()){
            return $this->setIsLayer(true)->success($this->service->searchList());
        }
        return $this->fetch();
    }

    public function add(){
        if ($this->request->isPost()){
            if ($this->service->save($this->request->param())){
                return $this->success("保存成功");
            }else{
                return $this->error("保存失败");
            }
        }
        return $this->fetch();
    }

    public function edit($id){
        if ($this->request->isPost()){
            if ($this->service->save($this->request->param())){
                return $this->success("保存成功");
            }else{
                return $this->error("保存失败");
            }
        }
        $this->assign($this->service->get($id));
        return $this->fetch('add');
    }

    public function batchRemove(){
        if ($this->service->goBatchRemove($this->request->param('ids'))){
            return $this->success("删除成功");
        }
        return $this->error("删除失败");
    }

    public function remove()
    {
        if ($this->service->remove($this->request->param('id'))){
            return $this->success("操作成功");
        }
        return $this->error("操作失败");
    }

    public function status()
    {
        if ($this->service->save($this->request->param())){
            return $this->success("操作成功");
        }
        return $this->error("操作失败");
    }

}