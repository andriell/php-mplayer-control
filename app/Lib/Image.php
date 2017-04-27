<?php
/**
 * Created by PhpStorm.
 * User: Андрей
 * Date: 27.04.2017
 * Time: 18:04
 */

namespace App\Lib;


class Image
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

    public function resize($uri, $newSize = [100, 100, false])
    {
        try {
            $realPathFile = $this->fs->realPath($uri);

            $ext = strtolower(substr($realPathFile, strrpos($realPathFile, '.') + 1));
            if ($ext == 'jpg' || $ext == 'jpeg') {
                $img1 = $this->override->imagecreatefromjpeg($realPathFile);
            } elseif ($ext == 'png') {
                $img1 = $this->override->imagecreatefrompng($realPathFile);
            } elseif ($ext == 'gif') {
                $img1 = $this->override->imagecreatefromgif($realPathFile);
            } elseif ($ext == 'bmp') {
                $img1 = $this->override->imagecreatefrombmp($realPathFile);
            } else {
                return;
            }

            list($w1, $h1) = $this->override->getimagesize($realPathFile);

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
                    $x2 = ($newSize[0] - $w2) / 2;
                }
                if ($h2 > $newSize[1]) {
                    $y2 = ($newSize[1] - $h2) / 2;
                }
                $img2 = imagecreatetruecolor($newSize[0], $newSize[1]);
                imagecopyresized($img2, $img1, $x2, $y2, 0, 0, $w2, $h2, $w1, $h1);
            } else {
                if ($w1 > $h1) {
                    $p = $newSize[0] / $w1;
                } else {
                    $p = $newSize[1] / $h1;
                }
                $w2 = $w1 * $p;
                $h2 = $h1 * $p;
                $img2 = imagecreatetruecolor($w2, $h2);
                imagecopyresized($img2, $img1, 0, 0, 0, 0, $w2, $h2, $w1, $h1);
            }

            imagejpeg($img2);
            imagedestroy($img2);
        } catch (\Exception $e) {
            $im = imagecreate($newSize[0], $newSize[1]);
            imagecolorallocate($im, 255, 255, 255);
            imagestring($im, 4, ($newSize[0] - 40) / 2, ($newSize[1] - 16) / 2, 'Error', imagecolorallocate($im, 0, 0, 0));
            imagejpeg($im);
            imagedestroy($im);
        }
    }
}