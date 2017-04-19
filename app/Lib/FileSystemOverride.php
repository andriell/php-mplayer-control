<?php
/**
 * Created by PhpStorm.
 * User: Андрей
 * Date: 19.04.2017
 * Time: 7:09
 */

namespace App\Lib;


class FileSystemOverride
{
    private $fileSystemEncoding;

    /**
     * FileSystemOverride constructor.
     * @param $fileSystemEncoding
     */
    public function __construct($fileSystemEncoding)
    {
        $this->fileSystemEncoding = $fileSystemEncoding;
    }


    public function fileperms($str)
    {
        if (empty($this->fileSystemEncoding)) {
            return fileperms($str);
        }
        return fileperms(iconv('UTF-8', $this->fileSystemEncoding, $str));
    }

    public function realpath($str)
    {
        if (empty($this->fileSystemEncoding)) {
            return realpath($str);
        }
        return iconv($this->fileSystemEncoding, 'UTF-8', realpath(iconv('UTF-8', $this->fileSystemEncoding, $str)));
    }

    public function is_dir($str)
    {
        if (empty($this->fileSystemEncoding)) {
            return is_dir($str);
        }
        return is_dir(iconv('UTF-8', $this->fileSystemEncoding, $str));
    }

    public function filemtime($str)
    {
        if (empty($this->fileSystemEncoding)) {
            return filemtime($str);
        }
        return filemtime(iconv('UTF-8', $this->fileSystemEncoding, $str));
    }

    public function filesize($str)
    {
        if (empty($this->fileSystemEncoding)) {
            return filesize($str);
        }
        return filesize(iconv('UTF-8', $this->fileSystemEncoding, $str));
    }

    public function opendir($str)
    {
        if (empty($this->fileSystemEncoding)) {
            return opendir($str);
        }
        return opendir(iconv('UTF-8', $this->fileSystemEncoding, $str));
    }

    public function readdir($str)
    {
        if (empty($this->fileSystemEncoding)) {
            return readdir($str);
        }
        $r = readdir($str);
        return is_bool($r) ? $r : iconv($this->fileSystemEncoding, 'UTF-8', $r);
    }

    public function imagecreatefromjpeg($str)
    {
        if (empty($this->fileSystemEncoding)) {
            return imagecreatefromjpeg($str);
        }
        return imagecreatefromjpeg(iconv('UTF-8', $this->fileSystemEncoding, $str));
    }

    public function imagecreatefrompng($str)
    {
        if (empty($this->fileSystemEncoding)) {
            return imagecreatefrompng($str);
        }
        return imagecreatefrompng(iconv('UTF-8', $this->fileSystemEncoding, $str));
    }

    public function imagecreatefromgif($str)
    {
        if (empty($this->fileSystemEncoding)) {
            return imagecreatefromgif($str);
        }
        return imagecreatefromgif(iconv('UTF-8', $this->fileSystemEncoding, $str));
    }

    public function imagecreatefrombmp($str)
    {
        if (empty($this->fileSystemEncoding)) {
            return imagecreatefrombmp($str);
        }
        return imagecreatefrombmp(iconv('UTF-8', $this->fileSystemEncoding, $str));
    }

    public function getimagesize($str)
    {
        if (empty($this->fileSystemEncoding)) {
            return getimagesize($str);
        }
        return getimagesize(iconv('UTF-8', $this->fileSystemEncoding, $str));
    }
}