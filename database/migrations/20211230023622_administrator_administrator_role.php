<?php

use think\migration\Migrator;
use think\migration\db\Column;

class AdministratorAdministratorRole extends Migrator
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
        $table = $this->table('administrator_administrator_role')->setComment("管理员-角色中间表");
        if (!$table->exists()){
            $table->addColumn('role_id','integer',['limit'=>11,'comment'=>'角色ID'])
                ->addColumn('admin_id','integer',['limit'=>11,'comment'=>'用户ID'])
                ->create();
        }
    }
}
