<?php

use think\migration\Migrator;
use think\migration\db\Column;

class AdministratorPermission extends Migrator
{
    /**
     * Change Method.
     *
     * Write your reversible migrations using this method.
     *
     * More information on writing migrations is available here:
     * http://docs.phinx.org/en/latest/migrations.html#the-abstractmigration-class
     *
     * The following commands can be used in this method and Phinx will
     * automatically reverse them when rolling back:
     *
     *    createTable
     *    renameTable
     *    addColumn
     *    renameColumn
     *    addIndex
     *    addForeignKey
     *
     * Remember to call "create()" or "update()" and NOT "save()" when working
     * with the Table class.
     */
    public function change()
    {
        $table = $this->table('administrator_permission')->setComment("权限表");
        if (!$table->exists()){
            $table->addColumn('pid','integer',['limit'=>11,'comment'=>'父级ID','default'=>0])
                ->addColumn('title','string',['limit'=>50,'comment'=>'名称','default'=>''])
                ->addColumn('href','string',['limit'=>50,'comment'=>'地址','default'=>''])
                ->addColumn('icon','string',['limit'=>50,'comment'=>'图标','default'=>''])
                ->addColumn('sort','integer',['limit'=>4,'comment'=>'排序','default'=>99])
                ->addColumn('type','integer',['limit'=>1,'comment'=>'菜单','default'=>1])
                ->addColumn('status','integer',['limit'=>1,'comment'=>'状态','default'=>1])
                ->addColumn('create_time','timestamp',['comment'=>'创建时间'])
                ->addIndex('pid')
                ->create();
        }
    }
}
