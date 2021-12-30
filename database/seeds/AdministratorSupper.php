<?php

use think\migration\Seeder;

class AdministratorSupper extends Seeder
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
        $service = new \app\services\AdministratorService();
        $this->table('administrator')->insert([
            'username'  => 'admin',
            'nickname'  => 'admin',
            'password'  => $service->passwordEncode('test12345678'),
            'status'    => 1,
            'token'     => '',
            'create_time'=>date('Y-m-d H:i:s'),
            'delete_time'=> null,
        ])->save();
    }
}