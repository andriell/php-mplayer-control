<?php
/**
 * Created by PhpStorm.
 * User: Андрей
 * Date: 03.12.2017
 * Time: 14:36
 */

namespace App\Lib;


class StackFile
{
    /** @var FileSystem */
    private $fs;
    /** @var string */
    private $file;
    /** @var int */
    private $limit;
    /** @var array */
    private $data;

    /**
     * StackFile constructor.
     * @param FileSystem $fs
     * @param string $file
     * @param int $limit
     */
    public function __construct($fs, $file, $limit = 5)
    {
        $this->fs = $fs;
        $this->file = $file;
        $this->limit = $limit < 2 ? 2 : $limit;
        $this->data = [];
        try {
            $this->data = json_decode(file_get_contents($file), true);
        } catch (\Exception $e) {
        }
    }

    public function add($uri)
    {
        $uri = $this->fs->normalizeUri($uri);
        foreach ($this->data as $i => $row) {
            if ($row['uri'] == $uri) {
                unset($this->data[$i]);
            }
        }
        if (count($this->data) >= $this->limit) {
            $this->data = array_slice($this->data, -1 * ($this->limit - 1));
        }
        $this->data[] = [
            'uri' => $uri,
            'name' => basename($this->fs->realPath($uri)),
            'data' => (new \DateTime())->format(\DateTime::ISO8601),
        ];
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
     * @return int
     */
    public function getLimit(): int
    {
        return $this->limit;
    }

    /**
     * @return array
     */
    public function getData()
    {
        return $this->data;
    }
}