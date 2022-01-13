<?php

namespace app\api\controller;

use app\services\file\FileService;

class Test extends ApiBaseController
{
    public function index(FileService $fileService){
// 获取表单上传文件
        $files = request()->file();
        try {
//            validate(['file'=>'fileSize:10240|fileExt:xlsx,csv'])
//                ->check($files);
            $fileService->upload($files);
            $res = $fileService->getSaveName();
//            foreach($files as $file) {
//                $savename[] = \think\facade\Filesystem::putFile( 'topic', $file);
//            }
            return $this->success('上传成功',['uri'=>$res]);
        } catch (\think\exception\ValidateException $e) {
            echo $e->getMessage();
            return $this->error("上传失败" . $e->getMessage());
        }
    }
}