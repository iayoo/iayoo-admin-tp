<?php

use think\migration\Migrator;
use think\migration\db\Column;

class Administrator extends Migrator
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
        $table = $this->table('administrator')->setComment("管理员表");
        if (!$table->exists()){
            $table->addColumn('username','string',['limit'=>30,'comment'=>'用户名，登陆使用'])
                  ->addColumn('password','string',['limit'=>120,'comment'=>'用户密码'])
                  ->addColumn('nickname','string',['limit'=>30,'comment'=>'用户昵称'])
                  ->addColumn('status','integer',['limit'=>1,'comment'=>'用户状态：1正常,2禁用 默认1','default'=>1])
                  ->addColumn('token','string',['limit'=>60,'comment'=>'token'])
                  ->addColumn('create_time','timestamp',['comment'=>'创建时间','null'=>true])
                  ->addColumn('update_time','timestamp',['comment'=>'更新时间','null'=>true])
                  ->addColumn('delete_time','timestamp',['comment'=>'删除时间','null'=>true])
                  ->create();
        }
    }
}
