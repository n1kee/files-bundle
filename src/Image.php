<?php

namespace FilesBundle;

use Imagick;
use ImagickDraw;

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

    function getClone() {
        $fileClone = clone $this;
        $fileClone->setFile($this->file->clone());
        return $fileClone;
    }

    public function addText(string $text)
    {
        $draw = new ImagickDraw;

        $rect = [
            'x' => 0,
            'y' => 0,
            'h' => $this->getHeight(),
            'w' => $this->getWidth(),
        ];

        /* Black text */
        $draw->setTextUnderColor('#ffffff');
        $draw->setFillColor('black');

        /* Font properties */
        $draw->setFont('Bookman-DemiItalic');
        $draw->setFontSize( 30 );

        $metrics = $this->file->queryFontMetrics($draw, $text);

        // Adjust starting x,y as needed to meet your requirements.
        $offset = [
            'x' => $rect['x'] + $rect['w'] / 2 - $metrics['textWidth'] / 2,
            'y' => $rect['y'] + $rect['h'] / 2 + $metrics['textHeight'] / 2 + $metrics['descender'],
        ];

        /* Create text */
        $this->file->annotateImage($draw, $offset["x"], $offset["y"], 0, $text);
    }
}