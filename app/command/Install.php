<?php
declare (strict_types = 1);

namespace app\command;

use think\console\Command;
use think\console\Input;
use think\console\input\Argument;
use think\console\input\Option;
use think\console\Output;
use think\facade\Db;

class Install extends Command
{
    protected function configure()
    {
        // 指令配置
        $this->setName('系统安装')
            ->setDescription('系统安装指令');
    }

    protected function execute(Input $input, Output $output)
    {
        // 指令输出
        $output->writeln('app\command\install');
        $this->start();

    }

    protected function start(){
        $res = $this->getConfig();
        if ($res){
            $res = shell_exec("php think migrate:run");
            $res = shell_exec("php think seed:run -s AdministratorSupper");
        }
    }

    protected function getConfig(){
        if (file_exists('.env')){
            return true;
        }
        $this->output->comment("配置文件未设置，将开始引导设置....");

        $dbHost = $this->output->ask($this->input,'Mysql 地址','127.0.0.1');
        $dbUser = $this->output->ask($this->input,'Mysql 账号','root');
        $dbPass = $this->output->ask($this->input,'Mysql 密码','root');
        $dbPort = $this->output->ask($this->input,'Mysql 端口','3306');
        $dbName = $this->output->ask($this->input,'Mysql 库名','');
        try{
            $mysqli = new \mysqli($dbHost, $dbUser, $dbPass);
        }catch (\Exception $e){
            $this->output->comment('数据库连接失败');
            return;
        }
        $mysqli->real_query('show databases;');

        $databaseExists = false;

        if ($result = $mysqli->use_result()) {
            $rows = $result->fetch_all();
            if ($rows){
                foreach ($rows as $row){
                    if ($row && isset($row[0])){
                        if ($row[0] === trim($dbName)){
                            $databaseExists = true;
                            break;
                        }
                    }
                }
            }
        }
        if ($databaseExists){
            $mysqli->real_query("use {$dbName};");
            $mysqli->real_query("show tables;");
            if ($tablesRes = $mysqli->use_result()) {
                $tables = $tablesRes->fetch_all();
                $isDrop = false;
                if ($tables){
                    $isDrop = $this->output->confirm($this->input,"数据库{$dbName}已存在，是否覆盖？(覆盖后数据将无法找回)",false);
                }
            }
        }
        $mysqli->real_query("create DATABASE '{$dbName}';");

        $envFile = file_get_contents('.example.env');
        $envFile = str_replace([
            '{{HOSTNAME}}',
            '{{DATABASE}}',
            '{{USERNAME}}',
            '{{PASSWORD}}',
            '{{HOSTPORT}}',
        ],[
            $dbHost,
            $dbName,
            $dbUser,
            $dbPass,
            $dbPort,
        ],$envFile);
        file_put_contents('.env',$envFile);
        return true;
    }
}
