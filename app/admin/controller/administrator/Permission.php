<?php
/**
 *
 */


namespace app\admin\controller\administrator;


use app\services\AdministratorPermissionService;
use app\services\ToolService;
use app\validate\admin\AdministratorPermission;

class Permission extends \app\admin\controller\BaseController
{
    public function index(AdministratorPermissionService $service){
        if ($this->request->isAjax()){
            $list = $service->getList();
            return $this->setIsLayer(true)->success(['code'=>0,'data'=>$list->toArray(),'extend'=>['count' => $list->count()]]);
        }

        $this->assign('datatableUrl',url(''));
        $cols = [
            [
                ['field'=> 'title', 'minWidth'=> 200, 'unresize'=> true,'title'=> '权限名称'],
                ['field'=> 'icon', 'align'=> 'center', 'unresize'=> true,'title'=> '图标','templet'=>'#icon-view'],
                ['field'=> 'type', 'title'=> '权限类型','unresize'=> true,'templet'=>'#power-type'],
                ['title'=> '状态', 'field'=> 'status','align'=> 'center','unresize'=> true,'templet'=> '#status','width'=> 100],
                ['field'=> 'sort', 'title'=> '排序','unresize'=> true],
                ['title'=> '操作','templet'=> '#power-bar', 'unresize'=> true,'width'=> 150, 'align'=> 'center']
            ]
        ];
        $renderConfig = [
            'treeColIndex'     => 0,
            'treeSpid'         => 0,
            'treeIdName'       => 'id',
            'treePidName'      => 'pid',
            'skin'             => 'line',
            'method'           => 'post',
            'treeDefaultClose' => false,
            'toolbar'          => '#power-toolbar',
            'elem'             => '#power-table',
            'url'              => request()->url(),
            'page'             => false,
            'cols'             => $cols
        ];
        $this->assign('renderConfig',json_encode($renderConfig));

        return $this->fetch();
    }

    public function getList(){
        return $this->success();
    }

    public function add(AdministratorPermissionService $service)
    {
        return $this->fetch('',[
            'permissions' => ToolService::getTree($service->getList())
        ]);
    }

    public function edit($id,AdministratorPermissionService $service){
        $this->assign('permissions',ToolService::getTree($service->getList()));
        return $this->fetch('',$service->get($id));
    }

    public function save(AdministratorPermissionService $service)
    {
        $params = $this->request->param();
        $validate = new AdministratorPermission();
        $validate->scene('create')->check($params);
        if ($service->save($params)){
            return $this->success("保存成功");
        }
        return $this->error('保存失败');
    }

    public function remove(AdministratorPermissionService $service){
        $res = $service->remove($this->request->param('id'),$this->request->param('type'));
        return $this->success("删除成功");
    }

    public function status(AdministratorPermissionService $service){
        if ($service->save($this->request->param())){
            return $this->success("操作成功");
        }
        return $this->error("操作失败");
    }
}