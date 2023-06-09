<?php

namespace FilesBundle;

use SplFileObject;
use FilesBundle\Helper\FileSystem;

/**
 * Wrapper class for SplFileObject objects.
 *
 */
class File
{
    protected $file;

    /**
     * Initializes the file.
     *
     * @param string $filePath Path of the file.
     * @return number $aaa
     */
    protected function initFile(string $filePath)
    {
        $this->file = new SplFileObject($filePath, "r+");
    }

    public function __construct($filePath = null)
    {
        $filePath = $filePath ?? tempnam(sys_get_temp_dir(), 'file');
        $this->initFile($filePath);
    }

    public function __destruct()
    {
        $this->file = null;
    }

    public function __call($name, $args)
    {
        return $this->file->{$name}(...$args);
    }

    /**
     * Writes a string of data into the file.
     *
     * @param string $data String of data to be written.
     */
    public function write(string $data)
    {
        $this->file->fwrite($data);
    }

    /**
     * Reads the entire file and returns it's content.
     *
     * @return string The content of the file.
     */
    public function read()
    {
        return file_get_contents($this->file->getRealPath());
    }

    /**
     * Reads the entire json file and parses it's content.
     *
     * @return array Parsed content of the file.
     */
    public function readJson()
    {
        return json_decode($this->read(), true);
    }

    /**
     * Get's the file.
     *
     * @return File
     */
    public function getFile()
    {
        return $this->file;
    }

    /**
     * Set's the file.
     *
     * @param File $file
     * @return File $file The saved file.
     */
    public function setFile($file)
    {
        return $this->file = $file;
    }

    /**
     * Get's a signature of the file.
     *
     * @return string File signature.
     */
    public function getFileSignature()
    {
        return hash_file('sha256', $this->file->getRealPath());
    }

    /**
     * Saves the file to the path specified.
     *
     * @param string $filePath Path for saving the file.
     */
    public function save(string $filePath)
    {
        $tmpFilePath = $this->file->getRealPath();
        $fileName = basename($filePath);
        $this->file->fflush();
        copy($tmpFilePath, $filePath);
        $this->initFile($filePath);
    }

    /**
     * Get's a clone of the wrapper object.
     *
     * @return File Cloned wrapper object.
     */
    public function getClone()
    {
        $fileClone = clone $this;
        $fileClone->setFile(clone $this->file);
        return $fileClone;
    }
}
