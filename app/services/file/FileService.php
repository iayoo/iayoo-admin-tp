<?php

namespace app\services\file;

class FileService extends \app\services\BaseService
{

    protected $saveName;

    /** @var int 上传类型：1=本地，2=CDN，3=本地和CDN */
    protected $mode = 1;

    /**
     * @return mixed
     */
    public function getSaveName()
    {
        return $this->saveName;
    }

    /**
     * @param mixed $saveName
     */
    public function setSaveName($saveName): void
    {
        $this->saveName = $saveName;
    }

    public function upload($file)
    {
        $this->saveName = \think\facade\Filesystem::disk('public')->putFile( 'uploads', $file,'md5');
        $this->afterUpload();
    }

    protected function afterUpload(){

    }


}