<?php
declare (strict_types = 1);

namespace app\command;

use DateTimeImmutable;
use think\console\Command;
use think\console\Input;
use think\console\input\Argument;
use think\console\input\Option;
use think\console\Output;
use think\facade\Db;

class Test extends Command
{
    protected function configure()
    {
        // 指令配置
        $this->setName('app\command\test')
            ->setDescription('the app\command\test command');
    }

    protected function execute(Input $input, Output $output)
    {
//        DateTimeImmutable::;
        $date = (DateTimeImmutable::createFromFormat('U.u', "1641351478.060648"));
        dump($date);
        $date = $date->setTimezone(new \DateTimeZone("Asia/Shanghai"));
        dump($date);
        die();
        // 指令输出
        $output->writeln('app\command\test');

        $permission = Db::name('administrator_permission')->order('id asc')->select();
        $json = json_encode($permission,JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES);
        dump($json);
        $json = str_replace('{','[',$json);
        $json = str_replace('}',']',$json);
        $json = str_replace(':','=>',$json);

        dump($json);

        file_put_contents('test.log',$json);


    }
}
