<?php

namespace FilesBundle;

use Imagick;
use ImagickDraw;

/**
 * Wrapper class for Imagick objects.
 *
 */
class Image extends File {

    protected $file;

    function __construct(string $filePath = null)
    {
        # For SVG
        # libmagickcore-6.q16-2-extra
        # potrace
        $this->file = new Imagick($filePath);
    }

    /**
     * Get's width of the image.
     *
     * @return float File width.
     */
    function getWidth(): float
    {
        return $this->file->getImageGeometry()["width"];
    }

    /**
     * Get's height of the image.
     *
     * @return float File height.
     */
    function getHeight(): float
    {
        return $this->file->getImageGeometry()["height"];
    }

    /**
     * Set's maximum width of the image and resizes it accordingly.
     *
     * @return bool Returns true on success.
     */
    function setMaxWidth(float $width): bool
    {
        if ($this->getWidth() > $width) {
           return $this->resize($width); 
        }
        return null;
    }

    /**
     * Set's maximum height of the image and resizes it accordingly.
     *
     * @return bool Returns true on success.
     */
    function setMaxHeight(float $height)
    {
        if ($this->getHeight() > $height) {
           return $this->resize(0, $height);
        }
        return null;
    }

    /**
     * Resizes the image to specific width and height.
     *
     * @param float $width  New width of the file.
     * @param float $height New height of the file.
     * @return bool Returns true on success.
     */
    function resize(float $width, float $height)
    {
        $this->file->resizeImage(
            $width ?? 0, 
            $height ?? 0,
            Imagick::FILTER_LANCZOS,
            1
        );
    }

    /**
     * Saves the file into the file system.
     *
     * @param string $imgPath Destination path.
     */
    function save(string $imgPath) {
        $this->file->writeImage($imgPath);
    }

    /**
     * Get's a clone of the wrapper object.
     *
     * @return Image
     */
    function getClone() {
        $fileClone = clone $this;
        $fileClone->setFile($this->file->clone());
        return $fileClone;
    }

    /**
     * Get's an ImagickDraw object for the specified text.
     *
     * @param string $text
     * @param textColor $text Background color for the text.
     * @return ImagickDraw
     */
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

    /**
     * Add's text at the center of an image.
     *
     * @param string $text Text to be added.
     * @param string $textColor Color of the text.
     */
    public function addCenteredText(string $text, string $textColor = null, string $bgColor = null)
    {
        $draw = $this->getTextDraw($text, $textColor, $bgColor);

        $metrics = $this->file->queryFontMetrics($draw, $text);

        $offsetX = $this->getWidth() / 2 - $metrics['textWidth'] / 2;
        $offsetY = $this->getHeight() / 2 + $metrics['textHeight'] / 2 + $metrics['descender'];

        $this->file->annotateImage($draw, $offsetX, $offsetY, 0, $text);
    }

    /**
     * Add's text at the bottom of an image.
     *
     * @param string $text Text to be added.
     * @param string $textColor Color of the text.
     */
    public function addBottomText(string $text, string $textColor, string $bgColor)
    {
        $draw = $this->getTextDraw($text, $textColor, $bgColor);

        $offsetX = 0;
        $offsetY = $this->getHeight() - 10;

        $this->file->annotateImage($draw, $offsetX, $offsetY, 0, $text);
    }
}