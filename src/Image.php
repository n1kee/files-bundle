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

    protected function getTextDraw(string $text, string $textColor = null, string $bgColor = null) {
        $draw = new ImagickDraw;

        /* Black text */
        if ($bgColor) $draw->setTextUnderColor($bgColor);
        if ($textColor) $draw->setFillColor($textColor);

        /* Font properties */
        $draw->setFont('Bookman-DemiItalic');
        $draw->setFontSize( 30 );

        return $draw;
    }

    public function addCenteredText(string $text, string $textColor = null, string $bgColor = null)
    {
        $draw = $this->getTextDraw($text, $textColor, $bgColor);

        $metrics = $this->file->queryFontMetrics($draw, $text);

        $offsetX = $this->getWidth() / 2 - $metrics['textWidth'] / 2;
        $offsetY = $this->getHeight() / 2 + $metrics['textHeight'] / 2 + $metrics['descender'];

        $this->file->annotateImage($draw, $offsetX, $offsetY, 0, $text);
    }

    public function addBottomText(string $text, string $textColor, string $bgColor)
    {
        $draw = $this->getTextDraw($text, $textColor, $bgColor);

        $offsetX = 0;
        $offsetY = $this->getHeight() - 10;

        $this->file->annotateImage($draw, $offsetX, $offsetY, 0, $text);
    }
}