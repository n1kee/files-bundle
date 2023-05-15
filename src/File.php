<?php

namespace FilesBundle;

use SplFileObject;
use FilesBundle\Helper\FileSystem;

class File {

    protected $file;

    protected function initFile(string $filePath) {
        $this->file = new SplFileObject($filePath, "r+");
    }
    
    function __construct($filePath = null)
    {
        $filePath = $filePath ?? tempnam(sys_get_temp_dir(), 'file');
        $this->initFile($filePath);
    }

    function __destruct() {
        $this->file = null;
    }

    function write(string $data) {
        $this->file->fwrite($data);
    }

    function read() {
        return file_get_contents($this->file->getRealPath());
    }

    function readJson() {
        return json_decode($this->read(), true);
    }

    function getFile() {
        return $this->file;
    }

    function setFile($file) {
        return $this->file = $file;
    }

    function getFileSignature() {
        return hash_file('sha256', $this->file->getRealPath());
    }

    function save(string $filePath) {
        $tmpFilePath = $this->file->getRealPath();
        $fileName = basename($filePath);
        $this->file->fflush();
        copy($tmpFilePath, $filePath);
        $this->initFile($filePath);
    }

    function getClone() {
        $fileClone = clone $this;
        $fileClone->setFile(clone $this->file);
        return $fileClone;
    }
}

