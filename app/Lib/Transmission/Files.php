<?php
/**
 * Created by PhpStorm.
 * User: Андрей
 * Date: 04.05.2017
 * Time: 22:53
 */

namespace App\Lib\Transmission;


class Files
{
    private $files = [
        'children' => []
    ];
    private $id = 0;

    function addFiles($files, $fileStats) {
        foreach ($files as $id => $file) {
            $this->addFile($file['name'], $file['length'], $file['bytesCompleted'], $id, $fileStats[$id]['wanted']);
        }
    }

    private function addFile($name, $size, $loaded, $id, $wanted) {
        $path = mb_split('/', $name);

        $f = &$this->files;
        foreach ($path as $i => $p) {
            $f = &$f['children'];
            if (!isset($f[$p])) {
                $f[$p] = [
                    'id' => 'a' . $this->id++,
                    'size' => 0,
                    'loaded' => 0,
                    'children' => [],
                    'wanted' => false,
                ];
            }
            $f[$p]['size'] += $size;
            $f[$p]['loaded'] += $loaded;
            $f = &$f[$p];
        }
        $f['id'] = $id;
        $f['wanted'] = $wanted;
    }

    function getData(&$children = false, &$childrenWanted = true) {
        if (!is_array($children)) {
            $children = $this->files['children'];
        }
        $r = [];
        foreach ($children as $name => $item) {
            $childrenWanted = $childrenWanted && $item['wanted'];
            $children = [];
            $wanted = $item['wanted'];
            if ($item['children']) {
                $wanted = true;
                $children = $this->getData($item['children'], $wanted);
            }
            $r[] = [
                'id' => $item['id'],
                'name' => $name,
                'size' => $item['size'],
                'loaded' => $item['loaded'],
                'wanted' => $wanted,
                'children' => $children,
            ];
        }
        return $r;
    }
}