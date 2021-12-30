<?php

use think\migration\Migrator;
use think\migration\db\Column;

class AdministratorLog extends Migrator
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
        $table = $this->table('administrator_log')->setComment("管理员行为日志");
        if (!$table->exists()){
            $table->addColumn('uid','integer',['limit'=>11,'comment'=>'管理员ID'])
                ->addColumn('url','string',['limit'=>255,'comment'=>'操作页面','default'=>''])
                ->addColumn('desc','text',['comment'=>'日志内容'])
                ->addColumn('ip','string',['limit'=>20,'comment'=>'操作IP','default'=>''])
                ->addColumn('user_agent','text',['comment'=>'User-Agent'])
                ->addColumn('create_time','timestamp',['comment'=>'创建时间','null'=>true])
                ->create();
        }
    }
}
