<?php
/**
 *
 */


namespace app\admin\controller;


use app\services\AdministratorService;
use app\services\ToolService;
use think\facade\Session;

class Index extends BaseController
{
    public function index(){
        return $this->fetch('');
    }

    // 欢迎页
    public function home(){
        return $this->fetch('',$this->getSystem());
    }

    /**
     * 菜单列表
     * @param AdministratorService $service
     */
    public function menu(AdministratorService $service){
        return $this->success(ToolService::getTree($service->permissions($service->getId(),request()->root())));
    }

    public function password(AdministratorService $service)
    {
        if ($this->request->isAjax()){
            if ($service->updatePassword($service->getId(),$this->request->param('password'))){
                return $this->success("操作成功");
            }
            return $this->error("操作失败");
        }
        return $this->fetch();
    }

    public function cache(ToolService $toolService)
    {
        if ($toolService::clearCache()){
            return $this->success('操作成功');
        }
        return $this->error("操作失败");
    }

    public function loading(){
        echo '<svg stroke="#000" width="50px" height="50px" viewBox="0 0 38 38" style="transform:scale(0.8);" xmlns="http://www.w3.org/2000/svg"><g fill="none" fill-rule="evenodd"><g transform="translate(1 1)" stroke-width="2"><circle stroke-opacity=".25" cx="18" cy="18" r="18"></circle><path d="M36 18c0-9.94-8.06-18-18-18"><animateTransform attributeName="transform" type="rotate" from="0 18 18" to="360 18 18" dur="1s" repeatCount="indefinite"></animateTransform></path></g></g></svg>';
    }
}