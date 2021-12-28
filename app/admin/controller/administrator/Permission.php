<?php
/**
 *
 */


namespace app\admin\controller\administrator;


use app\services\AdministratorPermissionService;

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
}