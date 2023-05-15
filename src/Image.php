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

    function getTextDraw(string $text) {
        $draw = new ImagickDraw;

        /* Black text */
        $draw->setTextUnderColor('#ffffff');
        $draw->setFillColor('black');

        /* Font properties */
        $draw->setFont('Bookman-DemiItalic');
        $draw->setFontSize( 30 );

        return $draw;
    }

    public function addCenteredText(string $text)
    {
        $draw = $this->getTextDraw($text);

        $metrics = $this->file->queryFontMetrics($draw, $text);

        $offsetX = $this->getWidth() / 2 - $metrics['textWidth'] / 2;
        $offsetY = $this->getHeight() / 2 + $metrics['textHeight'] / 2 + $metrics['descender'];

        $this->file->annotateImage($draw, $offsetX, $offsetY, 0, $text);
    }

    public function addBottomText(string $text)
    {
        $draw = $this->getTextDraw($text);

        $offsetX = 0;
        $offsetY = $this->getHeight() - 10;

        $this->file->annotateImage($draw, $offsetX, $offsetY, 0, $text);
    }
}