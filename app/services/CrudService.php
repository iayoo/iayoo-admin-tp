<?php


namespace app\services;


use think\facade\Db;

class CrudService
{
    // 获取所有表
    public static function getTable()
    {
        $list = [];
        foreach (Db::getTables() as $k =>$v) {
            $list[] = ['name'=>$v];
        }
        return ['code'=>0,'data'=>$list];
    }

    // 列表
    public static function getTableFields($name){
        return ['code'=>0,'data'=>Db::getFields($name)];
    }


    // 添加
    public static function addTable($data)
    {
        //数据验证
        if (!preg_match('/^[a-z]+_[a-z]+$/i',$data['name'])) return ['code' => 201, 'msg' => '表名格式不正确'];
        try {
            Db::execute('CREATE TABLE '.config('database.connections.mysql.prefix').$data['name'].'(
                `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT "id",
                `create_time` timestamp NULL DEFAULT NULL COMMENT "创建时间",
                `update_time` timestamp NULL DEFAULT NULL COMMENT "更新时间",
                `delete_time` timestamp NULL DEFAULT NULL COMMENT "删除时间",
                PRIMARY KEY (`id`)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 COMMENT="'.$data['desc'].'";
                ');
        }catch (\Exception $e){
            throw new \Exception($e->getMessage());
        }
        return true;
    }

    public static function removeTable($name,$type)
    {
        //验证
        if (in_array(substr($name,strlen(config('database.connections.mysql.prefix'))),self::$check)) return ['code'=>201,'msg'=>'默认字段禁止操作'];
        Db::query('drop table '.$name);
        if($type){
            try {
                $data['table'] = substr($name,strlen(config('database.connections.mysql.prefix')));
                //表名转驼峰
                $data['table_hump'] = underline_hump($data['table']);
                //左
                $data['left'] = strstr($data['table'], '_',true);
                //右
                $data['right'] = substr($data['table'],strlen($data['left'])+1);
                //右转驼峰
                $data['right_hump'] = underline_hump($data['right']);
                $commom = root_path().'app'.DS.'common'.DS;
                // 控制器
                $controller = app_path().'controller'.DS.$data['left'].DS.$data['right_hump'].'.php';
                if (file_exists($controller)) unlink($controller);
                // 模型
                $model = $commom.DS.'model'.DS.$data['table_hump'].'.php';
                if (file_exists($model)) unlink($model);
                // 服务
                $model = $commom.DS.'service'.DS.$data['table_hump'].'.php';
                if (file_exists($model)) unlink($model);
                // 验证器
                $model = $commom.DS.'validate'.DS.$data['table_hump'].'.php';
                if (file_exists($model)) unlink($model);
                //删除视图目录
                $view = root_path().'view'.DS.'admin'.DS.$data['left'].DS.$data['right'];
                if (file_exists($view)) delete_dir($view);
                //删除菜单
                (new \app\common\model\AdminPermission)->where('href', 'like', "%" . $data['left'].'.'.$data['right'] . "%")->delete();rm();
            }catch (\Exception $e){
                return ['msg'=>'删除失败','code'=>'201'];
            }
        }
    }
    // 获取Crud变量
    public static function getCrud($name)
    {
        $administratorPermissionService = new AdministratorPermissionService();

        return [
            'data' => self::getTableFields($name)['data'],
            'permissions' => ToolService::getTree($administratorPermissionService->getAll()),
            'desc' => Db::query('SELECT TABLE_COMMENT FROM information_schema.TABLES WHERE TABLE_NAME = "' . $name . '"')[0]['TABLE_COMMENT']
        ];
    }
}