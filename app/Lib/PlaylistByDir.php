<?php
/**
 * Created by PhpStorm.
 * User: Андрей
 * Date: 03.12.2017
 * Time: 14:36
 */

namespace App\Lib;


class PlaylistByDir
{
    /** @var FileSystem */
    private $fs;
    /** @var string */
    private $file;
    /** @var array */
    private $data;

    /**
     * PlaylistByDir constructor.
     * @param FileSystem $fs
     * @param string $file
     */
    public function __construct($fs, $file)
    {
        $this->fs = $fs;
        $this->file = $file;
        $this->data = [];
        try {
            $this->data = json_decode(file_get_contents($file), true);
        } catch (\Exception $e) {
        }
    }

    private function parseUri($uri) {
        $uri = $this->fs->normalizeUri($uri);
        $i = strrpos($uri, '/');
        $r = ['dir' => '/', 'name' => $uri];
        if ($i !== false) {
            $r['name'] = substr($uri, $i + 1);
            $r['dir'] = substr($uri, 0, $i + 1);
        }
        return $r;
    }

    public function add($uri)
    {
        $arr = $this->parseUri($uri);
        $this->data[$arr['dir']] = [
            'uri' => $uri,
            'name' => $arr['name'],
            'data' => (new \DateTime())->format(\DateTime::ISO8601),
        ];
    }

    public function rm($uri)
    {
        $arr = $this->parseUri($uri);
        if (isset($this->data[$arr['dir']])) {
            unset($this->data[$arr['dir']]);
        }
    }

    public function save()
    {
        file_put_contents($this->file, json_encode($this->data));
    }

    /**
     * @return string
     */
    public function getFile()
    {
        return $this->file;
    }

    /**
     * @return array
     */
    public function getData()
    {
        return $this->data;
    }
}