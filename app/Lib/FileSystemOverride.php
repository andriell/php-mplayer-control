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


    public function realpath($str)
    {
        return iconv($this->fileSystemEncoding, 'UTF-8', realpath(iconv('UTF-8', $this->fileSystemEncoding, $str)));
    }

    public function is_dir($str)
    {
        return is_dir(iconv('UTF-8', $this->fileSystemEncoding, $str));
    }

    public function filemtime($str)
    {
        return filemtime(iconv('UTF-8', $this->fileSystemEncoding, $str));
    }

    public function filesize($str)
    {
        return filesize(iconv('UTF-8', $this->fileSystemEncoding, $str));
    }

    public function opendir($str)
    {
        return opendir(iconv('UTF-8', $this->fileSystemEncoding, $str));
    }

    public function readdir($str)
    {
        $r = readdir($str);
        return is_bool($r) ? $r : iconv($this->fileSystemEncoding, 'UTF-8', $r);
    }

    public function imagecreatefromjpeg($str)
    {
        return imagecreatefromjpeg(iconv('UTF-8', $this->fileSystemEncoding, $str));
    }

    public function imagecreatefrompng($str)
    {
        return imagecreatefrompng(iconv('UTF-8', $this->fileSystemEncoding, $str));
    }

    public function imagecreatefromgif($str)
    {
        return imagecreatefromgif(iconv('UTF-8', $this->fileSystemEncoding, $str));
    }

    public function imagecreatefrombmp($str)
    {
        return imagecreatefrombmp(iconv('UTF-8', $this->fileSystemEncoding, $str));
    }

    public function getimagesize($str)
    {
        return getimagesize(iconv('UTF-8', $this->fileSystemEncoding, $str));
    }
}