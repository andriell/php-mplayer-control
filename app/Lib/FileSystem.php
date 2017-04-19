<?php
/**
 * Created by PhpStorm.
 * User: Андрей
 * Date: 19.04.2017
 * Time: 7:09
 */

namespace App\Lib;


class FileSystem
{
    private $mediaDir = '';
    private $fileSystemEncoding = '';

    private static $fileTypes = [
        'image' => array('png', 'jpg', 'gif', 'jpeg', 'bmp', 'svg'),
        'movie' => array(
            'avi', 'mkv', 'vob', 'mov', 'qt', 'wmv', 'mp4', 'm4p', 'm4v', 'mpg', 'mp2', 'mpeg', 'mpe', 'mpv',
            'mpg', 'mpeg', 'm2v', 'm4v', 'svi', '3gp', '3g2', 'mxf', 'roq', 'nsv', 'flv', 'f4v', 'f4p', 'f4a', 'f4b'
        ),
    ];

    /**
     * Uri constructor.
     * @param string $mediaDir
     */
    public function __construct($mediaDir, $fileSystemEncoding)
    {
        $this->mediaDir = rtrim(preg_replace('#[\\/]+#', '/', $mediaDir), '\\/');
        $this->fileSystemEncoding = $fileSystemEncoding;
    }

    /**
     * Нормальзует uri удаляя из него /../.
     * Если URI не лежит в пределах mediaDir то возвращает false
     * @param $uri
     * @return bool|string
     */
    private function normalizeUri($uri)
    {
        $uri = trim(preg_replace('#[\\/]+#', '/', $uri), '/');
        if (empty($uri)) {
            return '';
        }
        $r = realpath($this->mediaDir . '/' . $uri);
        if (strpos($r, $this->mediaDir) === 0) {
            return substr($r, strlen($this->mediaDir));
        }
        return false;
    }

    /**
     * Возвращает нормальзованный абсолютный путь удаляя из него /../.
     * Если URI не лежит в пределах mediaDir то возвращает false
     * @param $uri
     * @return bool|string
     */
    public function realPath($uri)
    {
        $uri = $this->normalizeUri($uri);
        if ($uri === false) {
            return false;
        }
        if (empty($uri)) {
            return $this->mediaDir;
        }
        return $this->mediaDir . '/' . $uri;
    }

    private function fileInfo($realPathFile)
    {
        $r = [
            'type' => false,
            'ext' => false,
            'size' => 0,
            'date' => date('Y-m-d H:i:s', filemtime($realPathFile)),
        ];
        if (is_dir($realPathFile)) {
            $r['type'] = 'dir';
            return $r;
        }
        $r['type'] = 'file';
        $r['ext'] = strtolower(substr($realPathFile, strrpos($realPathFile, '.') + 1));
        $r['size'] = filesize($realPathFile);
        foreach (static::$fileTypes as $type => &$ext) {
            if (in_array($r['ext'], $ext)) {
                $r['type'] = $type;
            }
        }
        return $r;
    }

    public function readDir($uri, $order = ['dir', 'name'])
    {
        $uri = $this->normalizeUri($uri);
        $r = [
            'uri' => $uri,
            'items' => [],
        ];
        if ($uri === false) {
            return $r;
        }
        $realPath = $this->mediaDir . '/' . $uri;
        if (!is_dir($realPath)) {
            return $r;
        }

        if ($handle = opendir($realPath)) {
            while (false !== ($entry = readdir($handle))) {
                if ($entry == '.' || $entry == '..') {
                    continue;
                }
                $realPathItem = $realPath . '/' . $entry;
                $item = $this->fileInfo($realPathItem);
                $item['name'] = iconv($this->fileSystemEncoding, 'UTF-8', $entry);
                $r['items'][] = $item;
            }
            closedir($handle);
        }
        usort($r['items'], function($a, $b) use($order) {
            foreach ($order as $o) {
                $r = 0;
                if ($o == 'dir') {
                    if ($a['type'] == 'dir') {
                        if ($b['type'] != 'dir') {
                            $r = -1;
                        }
                    } elseif ($b['type'] == 'dir') {
                        $r = 1;
                    }
                } elseif ($o == 'dir_desc') {
                    if ($a['type'] == 'dir') {
                        if ($b['type'] != 'dir') {
                            $r = 1;
                        }
                    } elseif ($b['type'] == 'dir') {
                        $r = -1;
                    }
                } elseif ($o == 'name') {
                    $r = strcasecmp($a['name'], $b['name']);
                } elseif ($o == 'name_desc') {
                    $r = strcasecmp($b['name'], $a['name']);
                } elseif ($o == 'date') {
                    $r = strcasecmp($a['date'], $b['date']);
                } elseif ($o == 'date_desc') {
                    $r = strcasecmp($b['date'], $a['date']);
                } elseif ($o == 'ext') {
                    $r = strcasecmp($a['ext'], $b['ext']);
                } elseif ($o == 'ext_desc') {
                    return strcasecmp($b['ext'], $a['ext']);
                } elseif ($o == 'size') {
                    $r = ($a['size'] < $b['size']) ? -1 : 1;
                } elseif ($o == 'size_desc') {
                    $r = ($a['size'] < $b['size']) ? 1 : -1;
                }
                if ($r !== 0) {
                    return $r;
                }
            }
            return 0;
        });
        return $r;
    }

    public function resizeImage($uri, $newSize = [100, 100]) {
        // файл и новый размер
        $uri = iconv('UTF-8', $this->fileSystemEncoding, $uri);
        $realPathFile = $this->realPath($uri);

        $ext = strtolower(substr($realPathFile, strrpos($realPathFile, '.') + 1));
        if ($ext == 'jpg' || $ext == 'jpeg') {
            $img1 = imagecreatefromjpeg($realPathFile);
        } elseif ($ext == 'png') {
            $img1 = imagecreatefrompng($realPathFile);
        } elseif ($ext == 'gif') {
            $img1 = imagecreatefromgif($realPathFile);
        } elseif ($ext == 'bmp') {
            $img1 = imagecreatefrombmp($realPathFile);
        } else {
            return;
        }

        list($w1, $h1) = getimagesize($realPathFile);
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

        imagejpeg($img2);
    }
}