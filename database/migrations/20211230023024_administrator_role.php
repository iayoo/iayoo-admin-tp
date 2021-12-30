<?php

use think\migration\Migrator;
use think\migration\db\Column;

class AdministratorRole extends Migrator
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
        $table = $this->table('administrator_role')->setComment("管理员角色表");
        if (!$table->exists()){
            $table->addColumn('name','string',['limit'=>30,'comment'=>'名称','default'=>''])
                ->addColumn('desc','string',['limit'=>100,'comment'=>'描述','default'=>''])
                ->addColumn('create_time','timestamp',['comment'=>'创建时间'])
                ->addColumn('update_time','timestamp',['comment'=>'更新时间'])
                ->addColumn('delete_time','timestamp',['comment'=>'删除时间'])
                ->create();
        }
    }
}
