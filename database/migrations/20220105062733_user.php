<?php

use think\migration\Migrator;
use think\migration\db\Column;

class User extends Migrator
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
        $table = $this->table('user')->setComment("用户表");
        $table->addColumn('nickname','string',['limit'=>255,'default'=>'','comment'=>'用户昵称'])
            ->addColumn('account','string',['limit'=>60,'default'=>'','comment'=>'登录账号'])
            ->addColumn('password','string',['limit'=>120,'default'=>'','comment'=>'登录密码'])
            ->addColumn('mobile','string',['limit'=>60,'default'=>'','comment'=>'手机号'])
            ->addColumn('email','string',['limit'=>60,'default'=>'','comment'=>'邮箱'])
            ->addColumn('sex','integer',['limit'=>1,'default'=>0,'comment'=>'性别'])
            ->addColumn('level','integer',['limit'=>1,'default'=>0,'comment'=>'用户等级'])
            ->addColumn('create_time','integer',['limit'=>11,'default'=>0,'comment'=>'创建时间'])
            ->create();

    }
}
