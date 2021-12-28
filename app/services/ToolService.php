<?php


namespace app\services;


class ToolService
{
    /**
     * 递归无限级分类权限
     * @param array $data
     * @param int $pid
     * @param string $field1 父级字段
     * @param string $field2 子级关联的父级字段
     * @param string $field3 子级键值
     * @return mixed
     */
    public static function getTree($data, $pid = 0, $field1 = 'id', $field2 = 'pid', $field3 = 'children')
    {
        $arr = [];
        foreach ($data as $k => $v) {
            if ($v[$field2] == $pid) {
                $v[$field3] = self::getTree($data, $v[$field1]);
                $arr[] = $v;
            }
        }
        return $arr;
    }

    /**
     *  随机数
     *
     * @param string $length 长度
     * @param int $type 类型
     * @return string
     */
    public static function rand_string($length = '32',$type=4): string
    {
        $rand='';
        switch ($type) {
            case '1':
                $randstr= '0123456789';
                break;
            case '2':
                $randstr= 'abcdefghijklmnopqrstuvwxyz';
                break;
            case '3':
                $randstr= 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
                break;
            default:
                $randstr= '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
                break;
        }
        $max = strlen($randstr)-1;
        mt_srand((double)microtime()*1000000);
        for($i=0;$i<$length;$i++) {
            $rand.=$randstr[mt_rand(0,$max)];
        }
        return $rand;
    }

    //密码截取
    public static function set_password($password): string
    {
        return substr(md5($password), 3, -3);
    }

    public static function is_url($url){
        if(preg_match("/^http(s)?:\\/\\/.+/",$url)) return $url;
    }
}