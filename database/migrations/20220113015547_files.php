<?php

use think\migration\Migrator;
use think\migration\db\Column;

class Files extends Migrator
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
        $table = $this->table('files')->setComment("文件管理表");
        $table->addColumn('path','string',['limit'=>255,'comment'=>'文件路径','default'=>''])
            ->addColumn('filename','string',['limit'=>120,'comment'=>'文件名','default'=>''])
            ->addColumn('ext','string',['limit'=>120,'comment'=>'文件拓展名','default'=>''])
            ->addColumn('size','integer',['limit'=>10,'comment'=>'文件大小（kb）','default'=>0])
            ->addColumn('year','string',['limit'=>4,'comment'=>'上传年份','default'=>0])
            ->addColumn('month','string',['limit'=>4,'comment'=>'上传月份','default'=>0])
            ->addColumn('day','string',['limit'=>4,'comment'=>'上传日期','default'=>0])
            ->addColumn('create_time','integer',['limit'=>11,'comment'=>'上传时间','default'=>0])
            ->create();
    }
}
