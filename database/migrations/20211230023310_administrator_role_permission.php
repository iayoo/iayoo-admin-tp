<?php

use think\migration\Migrator;
use think\migration\db\Column;

class AdministratorRolePermission extends Migrator
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
        $table = $this->table('administrator_role_permission')->setComment("角色-权限中间表");
        if (!$table->exists()){
            $table->addColumn('role_id','integer',['limit'=>11,'comment'=>'角色ID'])
                ->addColumn('permission_id','integer',['limit'=>11,'comment'=>'权限ID'])
                ->create();
        }
    }
}
