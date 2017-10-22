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
        if (!empty($this->fileSystemEncoding)) {
            $str = iconv('UTF-8', $this->fileSystemEncoding, $str);
        }
        $perms = fileperms($str);

        switch ($perms & 0xF000) {
            case 0xC000: // сокет
                $info = 's';
                break;
            case 0xA000: // символическая ссылка
                $info = 'l';
                break;
            case 0x8000: // обычный
                $info = 'r';
                break;
            case 0x6000: // файл блочного устройства
                $info = 'b';
                break;
            case 0x4000: // каталог
                $info = 'd';
                break;
            case 0x2000: // файл символьного устройства
                $info = 'c';
                break;
            case 0x1000: // FIFO канал
                $info = 'p';
                break;
            default: // неизвестный
                $info = 'u';
        }
        $info .= ': ';
        // Владелец
        $info .= (($perms & 0x0100) ? 'r' : '-');
        $info .= (($perms & 0x0080) ? 'w' : '-');
        $info .= (($perms & 0x0040) ?
            (($perms & 0x0800) ? 's' : 'x' ) :
            (($perms & 0x0800) ? 'S' : '-'));

        // Группа
        $info .= (($perms & 0x0020) ? 'r' : '-');
        $info .= (($perms & 0x0010) ? 'w' : '-');
        $info .= (($perms & 0x0008) ?
            (($perms & 0x0400) ? 's' : 'x' ) :
            (($perms & 0x0400) ? 'S' : '-'));

        // Мир
        $info .= (($perms & 0x0004) ? 'r' : '-');
        $info .= (($perms & 0x0002) ? 'w' : '-');
        $info .= (($perms & 0x0001) ?
            (($perms & 0x0200) ? 't' : 'x' ) :
            (($perms & 0x0200) ? 'T' : '-'));

        return $info;
    }

    public function is_dir($str)
    {
        if (empty($this->fileSystemEncoding)) {
            return is_dir($str);
        }
        return is_dir(iconv('UTF-8', $this->fileSystemEncoding, $str));
    }

    public function readlink($str)
    {
        if (empty($this->fileSystemEncoding)) {
            return readlink($str);
        }
        return readlink(iconv('UTF-8', $this->fileSystemEncoding, $str));
    }

    public function is_link($str)
    {
        if (empty($this->fileSystemEncoding)) {
            return is_link($str);
        }
        return is_link(iconv('UTF-8', $this->fileSystemEncoding, $str));
    }

    public function is_file($str)
    {
        if (empty($this->fileSystemEncoding)) {
            return is_file($str);
        }
        return is_file(iconv('UTF-8', $this->fileSystemEncoding, $str));
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