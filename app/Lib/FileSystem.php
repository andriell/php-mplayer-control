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
    public function __construct($mediaDir)
    {
        $this->mediaDir = rtrim(preg_replace('#[\\/]+#', '/', $mediaDir), '/');
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
    private function realPath($uri)
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
            'time' => date('Y-m-d H:i:s', filemtime($realPathFile)),
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

    public function readDir($uri)
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
                $entry = iconv('CP1251', 'UTF-8', $entry);
                $realPathItem = $realPath . '/' . $entry;
                $item = $this->fileInfo($realPathItem);
                $item['name'] = $entry;
                $r['items'][] = $item;
            }
            closedir($handle);
        }

        return $r;
    }
}