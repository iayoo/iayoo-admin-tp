<?php

use think\migration\Seeder;

class AdministratorPermission extends Seeder
{
    /**
     * Run Method.
     *
     * Write your database seeder using this method.
     *
     * More information on writing seeders is available here:
     * http://docs.phinx.org/en/latest/seeding.html
     */
    public function run()
    {
        $this->table('administrator_permission')
            ->insert([
                ["id" => 1, "pid" => 36, "title" => "后台权限", "href" => "", "icon" => "layui-icon layui-icon layui-icon-username", "sort" => 2, "type" => 0, "status" => 1],
                ["id" => 2, "pid" => 1, "title" => "管理员", "href" => "/administrator.Administrator/index", "icon" => "", "sort" => 1, "type" => 1, "status" => 1],
                ["id" => 3, "pid" => 2, "title" => "新增管理员", "href" => "/administrator.admin/add", "icon" => "", "sort" => 1, "type" => 1, "status" => 1],
                ["id" => 4, "pid" => 2, "title" => "编辑管理员", "href" => "/administratoradmin/edit", "icon" => "", "sort" => 1, "type" => 1, "status" => 1],
                ["id" => 5, "pid" => 2, "title" => "修改管理员状态", "href" => "/administrator.admin/status", "icon" => "", "sort" => 1, "type" => 1, "status" => 1],
                ["id" => 6, "pid" => 2, "title" => "删除管理员", "href" => "/administrator.admin/remove", "icon" => "", "sort" => 1, "type" => 1, "status" => 1],
                ["id" => 7, "pid" => 2, "title" => "批量删除管理员", "href" => "/administrator.admin/batchRemove", "icon" => "", "sort" => 1, "type" => 1, "status" => 1],
                ["id" => 8, "pid" => 2, "title" => "管理员分配角色", "href" => "/administrator.admin/role", "icon" => "", "sort" => 1, "type" => 1, "status" => 1],
                ["id" => 9, "pid" => 2, "title" => "管理员分配直接权限", "href" => "/administrator.admin/permission", "icon" => "", "sort" => 1, "type" => 1, "status" => 1],
                ["id" => 10, "pid" => 2, "title" => "管理员回收站", "href" => "/administrator.admin/recycle", "icon" => "", "sort" => 1, "type" => 1, "status" => 1],
                ["id" => 11, "pid" => 1, "title" => "角色管理", "href" => "/administrator.role/index", "icon" => "", "sort" => 99, "type" => 1, "status" => 1],
                ["id" => 12, "pid" => 11, "title" => "新增角色", "href" => "/administrator.role/add", "icon" => "", "sort" => 99, "type" => 1, "status" => 1],
                ["id" => 13, "pid" => 11, "title" => "编辑角色", "href" => "/administrator.role/edit", "icon" => "", "sort" => 99, "type" => 1, "status" => 1],
                ["id" => 14, "pid" => 11, "title" => "删除角色", "href" => "/administrator.role/remove", "icon" => "", "sort" => 99, "type" => 1, "status" => 1],
                ["id" => 15, "pid" => 11, "title" => "角色分配权限", "href" => "/administrator.role/permission", "icon" => "", "sort" => 99, "type" => 1, "status" => 1],
                ["id" => 16, "pid" => 11, "title" => "角色回收站", "href" => "/administrator.role/recycle", "icon" => "", "sort" => 99, "type" => 1, "status" => 1],
                ["id" => 17, "pid" => 1, "title" => "菜单权限", "href" => "/administrator.permission/index", "icon" => "", "sort" => 99, "type" => 1, "status" => 1],
                ["id" => 18, "pid" => 17, "title" => "新增菜单", "href" => "/administrator.permission/add", "icon" => "", "sort" => 99, "type" => 1, "status" => 1],
                ["id" => 19, "pid" => 17, "title" => "编辑菜单", "href" => "/administrator.permission/edit", "icon" => "", "sort" => 99, "type" => 1, "status" => 1],
                ["id" => 20, "pid" => 17, "title" => "修改菜单状态", "href" => "/administrator.permission/status", "icon" => "", "sort" => 99, "type" => 1, "status" => 1],
                ["id" => 21, "pid" => 17, "title" => "删除菜单", "href" => "/administrator.permission/remove", "icon" => "", "sort" => 99, "type" => 1, "status" => 1],
                ["id" => 22, "pid" => 36, "title" => "系统管理", "href" => "", "icon" => "layui-icon layui-icon layui-icon-set", "sort" => 3, "type" => 0, "status" => 1],
                ["id" => 23, "pid" => 22, "title" => "后台日志", "href" => "/administrator.administrator/log", "icon" => "", "sort" => 2, "type" => 1, "status" => 1],
                ["id" => 24, "pid" => 23, "title" => "清空管理员日志", "href" => "/administrator.administrator/removeLog", "icon" => "", "sort" => 1, "type" => 1, "status" => 1],
                ["id" => 25, "pid" => 22, "title" => "系统设置", "href" => "/config/index", "icon" => "", "sort" => 1, "type" => 1, "status" => 1],
                ["id" => 26, "pid" => 22, "title" => "图片管理", "href" => "/administrator.photo/index", "icon" => "", "sort" => 2, "type" => 1, "status" => 1],
                ["id" => 27, "pid" => 26, "title" => "新增图片文件夹", "href" => "/administrator.photo/add", "icon" => "", "sort" => 2, "type" => 1, "status" => 1],
                ["id" => 28, "pid" => 26, "title" => "删除图片文件夹", "href" => "/administrator.photo/del", "icon" => "", "sort" => 2, "type" => 1, "status" => 1],
                ["id" => 29, "pid" => 26, "title" => "图片列表", "href" => "/administrator.photo/list", "icon" => "", "sort" => 2, "type" => 1, "status" => 1],
                ["id" => 30, "pid" => 26, "title" => "添加单图", "href" => "/administrator.photo/addPhoto", "icon" => "", "sort" => 2, "type" => 1, "status" => 1],
                ["id" => 31, "pid" => 26, "title" => "添加多图", "href" => "/administrator.photo/addPhotos", "icon" => "", "sort" => 2, "type" => 1, "status" => 1],
                ["id" => 32, "pid" => 26, "title" => "删除图片", "href" => "/administrator.photo/remove", "icon" => "", "sort" => 2, "type" => 1, "status" => 1],
                ["id" => 33, "pid" => 26, "title" => "批量删除图片", "href" => "/administrator.photo/batchRemove", "icon" => "", "sort" => 2, "type" => 1, "status" => 1],
                ["id" => 36, "pid" => 0, "title" => "系统设置", "href" => "", "icon" => "layui-icon layui-iconlayui-icon-face-smile", "sort" => 1, "type" => 0, "status" => 1]
            ])->save();
    }
}