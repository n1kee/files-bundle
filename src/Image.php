<?php

namespace FilesBundle;

use Imagick;

class Image extends File {

    protected $file;

    function __construct(string $filePath = null)
    {
        $this->file = new Imagick($filePath);
    }

    function __call($name, $args) {
        return $this->file->{$name}(...$args);
    }

    function getWidth(): string
    {
        return $this->file->getImageGeometry()["width"];
    }

    function getHeight(): float
    {
        return $this->file->getImageGeometry()["height"];
    }

    function setMaxWidth(float $width): float
    {
        if ($this->getWidth() > $width) {
           return $this->resize($width); 
        }
        return null;
    }

    function setMaxHeight(float $height)
    {
        if ($this->getHeight() > $height) {
           return $this->resize(0, $height);
        }
        return null;
    }


    function resize(int $width, int $height)
    {
        $this->file->resizeImage(
            $width ?? 0, 
            $height ?? 0,
            Imagick::FILTER_LANCZOS,
            1
        );
    }

    function save(string $imgPath) {
        $this->file->writeImage($imgPath);
    }
}