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
}