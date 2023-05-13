<?php

namespace FilesBundle;

use SplFileObject;
use FilesBundle\Helper\FileSystem;

class File {

    private $file;

    protected function initFile(string $filePath) {
        $this->file = new SplFileObject($filePath, "r+");
    }
    
    function __construct($filePath = null)
    {
        $filePath = $file ?? tempnam(sys_get_temp_dir(), 'file');
        $this->initFile($filePath);
    }

    function __destruct() {
        $this->file = null;
    }

    function write(string $data) {
        $this->file->fwrite($data);
    }

    function save(string $filePath) {
        $tmpFilePath = $this->file->getRealPath();
        $fileName = basename($tmpFilePath);
        $fullFilePath = FileSystem::createPath($filePath, $fileName);
        $this->file->fflush();
        rename($tmpFilePath, $fullFilePath);
        $this->initFile($fullFilePath);
    }
}

