<?php

namespace app\admin\controller\file;

use app\admin\controller\BaseController;
use app\services\file\FileService;

class File extends BaseController
{
    /** @var FileService */
    protected $service = FileService::class;

    public function upload()
    {
        // 获取表单上传文件
        $files = request()->file();
        try {
            validate(['file'=>'fileSize:10240|fileExt:xlsx,csv'])
                ->check($files);
            $this->service->upload($files);
            $res = $this->service->getSaveName();
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