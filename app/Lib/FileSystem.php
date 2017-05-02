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
    private $override = '';

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
     * @param FileSystemOverride $override
     */
    public function __construct($mediaDir, FileSystemOverride $override)
    {
        $this->mediaDir = rtrim(preg_replace('#[\\\\/]+#', '/', $mediaDir), '\\/');
        $this->override = $override;
    }

    /**
     * Нормальзует uri удаляя из него /../.
     * Если URI не лежит в пределах mediaDir то возвращает false
     * @param $uri
     * @return bool|string
     */
    private function normalizeUri($uri)
    {
        $uri = preg_replace('#[\\\\/]+#', '/', $uri);
        $uri = preg_replace('#/[\\.]+/#', '/', $uri);
        return trim($uri, '/');
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

    public function fileMTime($uri)
    {
        $realPathFile = $this->realPath($uri);
        if (empty($realPathFile)) {
            return false;
        }
        return $this->override->filemtime($realPathFile);
    }

    private function fileInfo($realPathFile, $name, $filter = [])
    {
        if (isset($filter['name'])) {
            /** @var StarPattern $pattern */
            $pattern = $filter['name'];
            if (!$pattern->match($name)) {
                return false;
            }
        }

        $fileMTime = $this->override->filemtime($realPathFile);
        if (isset($filter['date>']) && $fileMTime < $filter['date>']) {
            return false;
        }
        if (isset($filter['date<']) && $fileMTime > $filter['date<']) {
            return false;
        }
        $r = [
            'name' => $name,
            'type' => false,
            'ext' => false,
            'size' => 0,
            'date' => date('Y-m-d H:i:s', $fileMTime),
            'perms' => $this->override->fileperms($realPathFile),
        ];
        if ($this->override->is_dir($realPathFile)) {
            $r['type'] = 'dir';
            return $r;
        } elseif (isset($filter['only_dir'])) {
            return false;
        }
        $r['type'] = 'file';
        $r['ext'] = strtolower(substr($realPathFile, strrpos($realPathFile, '.') + 1));
        $r['size'] = $this->override->filesize($realPathFile);
        if (isset($filter['size>']) && $r['size'] < $filter['size>']) {
            return false;
        }
        if (isset($filter['size<']) && $r['size'] > $filter['size<']) {
            return false;
        }
        foreach (static::$fileTypes as $type => &$ext) {
            if (in_array($r['ext'], $ext)) {
                $r['type'] = $type;
            }
        }
        if (isset($filter['type']) && !in_array($r['type'], $filter['type'])) {
            return false;
        }
        return $r;
    }

    public function readDir($uri, $filter = [], $order = ['dir', 'name'], $limit = [0, 1000000])
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
        if (!$this->override->is_dir($realPath)) {
            return $r;
        }

        //<editor-fold desc="Нормализация фильтров">
        if (isset($filter['name'])) {
            $filter['name'] = new StarPattern($filter['name']);
        }
        if (isset($filter['date>'])) {
            $filter['date>'] = strtotime($filter['date>']);
        }
        if (isset($filter['date<'])) {
            $filter['date<'] = strtotime($filter['date<']);
        }
        if (isset($filter['type']) && !is_array($filter['type'])) {
            $filter['type'] = [$filter['type']];
        }
        //</editor-fold>

        if ($handle = $this->override->opendir($realPath)) {
            while (false !== ($entry = $this->override->readdir($handle))) {
                if ($entry == '.' || $entry == '..') {
                    continue;
                }
                $item = $this->fileInfo($realPath . '/' . $entry, $entry, $filter);
                if (empty($item)) {
                    continue;
                }
                if ($limit[0] > 0) {
                    $limit[0]--;
                    continue;
                }
                if ($limit[1] <= 0) {
                    $limit[1]--;
                    break;
                }
                $r['items'][] = $item;
            }
            closedir($handle);
        }

        usort($r['items'], function ($a, $b) use ($order) {
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

    /**
     * Rename
     * Если целевой файл уже существует, то к его названию в конце будет добавлен номер .~n~
     * @param string $uriDir
     * @param string $oldFileName
     * @param string $newFileName
     * @return bool
     */
    function fileRename($uriDir, $oldFileName, $newFileName)
    {
        return $this->mvBackupNumbered($uriDir . '/' . $oldFileName, $uriDir . '/' . $newFileName);
    }

    function newFolder($uriFrom, $uriTo)
    {
        $to = $this->realPath($uriTo);
        Shell::exec('mkdir "' . str_replace('"', '', $to) . '"');
        if (empty($uriFrom)) {
            return true;
        }
        return $this->mvBackupNumbered($uriFrom, $uriTo);
    }

    /**
     * Переместить только что загруженный файл
     * Если целевой файл уже существует, то к его названию в конце будет добавлен номер .~n~
     * @param string $realPathFrom - путь к файлу в папке tmp
     * @param string $uriTo - путь к новому файлу
     * @return bool
     */
    function mvUpload($realPathFrom, $uriTo)
    {
        $realPathFrom = '/' . $this->normalizeUri($realPathFrom);
        $realPathTo = $this->realPath($uriTo);
        if (!(is_file($realPathFrom) && substr($realPathFrom, 0, 5) == '/tmp/' && $realPathTo)) {
            return false;
        }
        Shell::exec('mv --backup=numbered "' . str_replace('"', '', $realPathFrom) . '" "' . str_replace('"', '', $realPathTo) . '"');
        return true;
    }

    /**
     * Переместить или переименовать.
     * Если целевой файл уже существует, то к его названию в конце будет добавлен номер .~n~
     * @param string[]|string $uriFrom
     * @param string $uriTo
     * @return bool|int
     */
    function mvBackupNumbered($uriFrom, $uriTo)
    {
        $to = $this->realPath($uriTo);
        if (empty($to)) {
            return false;
        }
        $uriFrom = is_array($uriFrom) ? $uriFrom : [$uriFrom];
        $i = 0;
        foreach ($uriFrom as $uri) {
            $from = $this->realPath($uri);
            if (empty($from)) {
                continue;
            }
            // --backup=numbered при совпадении имен нумеровать
            Shell::exec('mv --backup=numbered "' . str_replace('"', '', $from) . '" "' . str_replace('"', '', $to) . '"');
            $i++;
        }
        return $i;
    }

    /**
     * Копировать
     * Если целевой файл уже существует, то к его названию в конце будет добавлен номер .~n~
     * @param string[]|string $uriFrom
     * @param string $uriTo
     * @return bool|int
     */
    function cpBackupNumbered($uriFrom, $uriTo)
    {
        $to = $this->realPath($uriTo);
        if (empty($to)) {
            return false;
        }
        $uriFrom = is_array($uriFrom) ? $uriFrom : [$uriFrom];
        $i = 0;
        foreach ($uriFrom as $uri) {
            $from = $this->realPath($uri);
            // --backup=numbered при совпадении имен нумеровать
            Shell::exec('cp -r --backup=numbered "' . str_replace('"', '', $from) . '" "' . str_replace('"', '', $to) . '"');
            $i++;
        }
        return $i;
    }

    function rm($uri)
    {
        $uri = is_array($uri) ? $uri : [$uri];
        $i = 0;
        foreach ($uri as $u) {
            $realPath = $this->realPath($u);
            Shell::exec('rm -rv "' . str_replace('"', '', $realPath) . '"');
            $i++;
        }
        return $i;
    }
}