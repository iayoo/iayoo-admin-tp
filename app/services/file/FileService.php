<?php

namespace app\services\file;

use app\model\Files;

class FileService extends \app\services\BaseService
{
    /** @var Files  */
    protected $model = Files::class;

    protected $saveName;

    protected $saveFile;

    /** @var int 上传类型：1=本地，2=CDN，3=本地和CDN */
    protected $mode = 1;

    /**
     * @return mixed
     */
    public function getSaveName()
    {
        if (count($this->saveName) > 0){
            return $this->saveName[count($this->saveName)-1];
        }
        return $this->saveName;
    }

    /**
     * @param mixed $saveName
     */
    public function setSaveName($saveName): void
    {
        $this->saveName = $saveName;
    }

    public function upload($files)
    {
        if (is_array($files)){
            foreach ($files as $file){

                $path = \think\facade\Filesystem::disk('public')->putFile( 'uploads', $file,'md5');
                $this->saveName[] = "/storage/" . $path;
                $this->saveFile[] = [
                    'filename' => $file->getOriginalName(),
                    'ext'      => $file->getOriginalExtension(),
                    'size'     => $file->getSize(),
                    'path'     => "/storage/" . $path,
                ];
            }
        }
        $this->afterUpload();
    }

    protected function afterUpload(){
        $this->saveDB();
    }

    protected function saveDB(){
        $dateInfo = [
            'year'        => date('Y'),
            'month'       => date('m'),
            'day'         => date('d'),
            'create_time' => time(),
        ];
        $insert = [];
        foreach ($this->saveFile as $item){
            $insert[] = array_merge($dateInfo,$item);
        }
        $this->modelInstance->insertAll($insert);
    }


}