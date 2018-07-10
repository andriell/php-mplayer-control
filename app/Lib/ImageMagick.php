<?php
/**
 * Created by PhpStorm.
 * User: Андрей
 * Date: 27.04.2017
 * Time: 18:04
 */

namespace App\Lib;


class ImageMagick
{
    /** @var FileSystem */
    private $fs;
    /** @var string FileSystemOverride */
    private $override;

    /**
     * MPlayer constructor.
     * @param FileSystem $fs
     * @param FileSystemOverride $override
     */
    public function __construct(FileSystem $fs, FileSystemOverride $override)
    {
        $this->fs = $fs;
        $this->override = $override;
    }

    public function imgError($newSize)
    {
        $image = new \Imagick();
        $image->newImage($newSize[0], $newSize[1], new \ImagickPixel('#FFFFFF'));

        /* Черный текст */
        $draw = new \ImagickDraw();
        $draw->setFillColor(new \ImagickPixel('#000000'));
        $draw->setFont('Courier');
        $draw->setFontSize(12);
        $image->annotateImage($draw, ($newSize[0] - 40) / 2, ($newSize[1] - 16) / 2, 0, 'Error');
        $image->setImageFormat('jpg');
        return $image->getImageBlob();
    }

    public function resize($uri, $newSize = [100, 100, false])
    {
        $realPathFile = $this->fs->realPath($uri);
        $imagick = new \Imagick($realPathFile);
        $w1 = $imagick->getImageWidth();
        $h1 = $imagick->getImageHeight();
        if (isset($newSize[2]) && $newSize[2]) {
            if ($w1 < $h1) {
                $p = $newSize[0] / $w1;
            } else {
                $p = $newSize[1] / $h1;
            }
            $w2 = $w1 * $p;
            $h2 = $h1 * $p;
            $x2 = 0;
            $y2 = 0;
            if ($w2 > $newSize[0]) {
                $x2 = ($w2 - $newSize[0]) / 2;
            }
            if ($h2 > $newSize[1]) {
                $y2 = ($h2 - $newSize[1]) / 2;
            }
            $imagick->scaleImage($w2, $h2, false);
            $imagick->cropImage($newSize[0], $newSize[1], $x2, $y2);
        } else {
            if ($w1 > $h1) {
                $p = $newSize[0] / $w1;
            } else {
                $p = $newSize[1] / $h1;
            }
            $w2 = $w1 * $p;
            $h2 = $h1 * $p;
            $imagick->scaleImage($w2, $h2, false);
        }

        $orientation = $imagick->getImageOrientation();
        $rotate = 0;
        if ($orientation == \Imagick::ORIENTATION_BOTTOMRIGHT) {
            $rotate = 180;
        } elseif ($orientation == \Imagick::ORIENTATION_RIGHTTOP) {
            $rotate = 90;
        } elseif ($orientation == \Imagick::ORIENTATION_LEFTBOTTOM) {
            $rotate = -90;
        }
        if ($rotate != 0) {
            $imagick->rotateImage('#000000', $rotate);
            $imagick->setImageOrientation(\Imagick::ORIENTATION_TOPLEFT);
        }

        $imagick->setImageFormat('jpg');
        return $imagick->getImageBlob();
    }
}
