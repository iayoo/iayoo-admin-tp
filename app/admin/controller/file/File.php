<?php

namespace app\admin\controller\file;

use app\admin\controller\BaseController;
use app\services\file\FileService;

class File extends BaseController
{
    /** @var FileService */
    protected $service = FileService::class;

    public function index()
    {
        if ($this->request->isAjax()){
            return $this->success($this->service->searchList([]));
        }
    }

    public function upload()
    {
        // 获取表单上传文件
        $files = request()->file();
        try {
            validate(['file'=>'fileSize:1048576'])
                ->check($files);
            $this->service->upload($files);
            $res = $this->service->getSaveFile();
//            foreach($files as $file) {
//                $savename[] = \think\facade\Filesystem::putFile( 'topic', $file);
//            }
            return $this->success('上传成功',$res);
        } catch (\think\exception\ValidateException $e) {
            echo $e->getMessage();
            return $this->error("上传失败" . $e->getMessage());
        }
    }
}