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

    function addFiles($files) {
        foreach ($files as $id => $file) {
            $this->addFile($file['name'], $file['length'], $file['bytesCompleted'], $id);
        }
    }

    function addFile($name, $size, $loaded, $id) {
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
                ];
            }
            $f[$p]['size'] += $size;
            $f[$p]['loaded'] += $loaded;
            $f = &$f[$p];
        }
        $f['id'] = $id;
    }

    function getData($children = false) {
        if (!is_array($children)) {
            $children = $this->files['children'];
        }
        $r = [];
        foreach ($children as $name => $item) {
            $r[] = [
                'id' => $item['id'],
                'name' => $name,
                'size' => $item['size'],
                'loaded' => $item['loaded'],
                'children' => $item['children'] ? $this->getData($item['children']) : [],
            ];
        }
        return $r;
    }
}