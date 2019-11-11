<?php

// +----------------------------------------------------------------------
// | EasyAdmin
// +----------------------------------------------------------------------
// | PHP交流群: 763822524
// +----------------------------------------------------------------------
// | 开源协议  https://mit-license.org 
// +----------------------------------------------------------------------
// | github开源项目：https://github.com/zhongshaofa/EasyAdmin
// +----------------------------------------------------------------------

namespace EasyAdmin\upload\driver;

use EasyAdmin\upload\Base;
use EasyAdmin\upload\driver\alioss\Oss;
use EasyAdmin\upload\trigger\SaveDb;

class Alioss extends Base
{

    public function save()
    {
        parent::save();
        $upload = Oss::instance()
            ->setConfig($this->uploadConfig)
            ->save($this->completeFilePath);

        if ($upload['save'] == true) {
            SaveDb::trigger($this->tableName, [
                'upload_type'   => $this->uploadType,
                'original_name' => $this->file->getOriginalMime(),
                'mime_type'     => $this->file->getOriginalMime(),
                'file_ext'      => strtolower($this->file->getOriginalExtension()),
                'url'           => $upload['url'],
            ]);
        }

        $this->rmLocalSave();

        return $upload;
    }

}