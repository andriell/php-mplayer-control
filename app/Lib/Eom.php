<?php
/**
 * Created by PhpStorm.
 * User: Андрей
 * Date: 19.04.2017
 * Time: 17:40
 */

namespace App\Lib;


class Eom
{
    /** @var FileSystem */
    private $fs;

    /**
     * MPlayer constructor.
     * @param FileSystem $fs
     */
    public function __construct(FileSystem $fs)
    {
        $this->fs = $fs;
    }

    function openFile($uri)
    {
        $path = $this->fs->realPath($uri);
        Shell::exec('killall eom');
        Shell::exec('export DISPLAY=:0.0 && eom -f "' . str_replace('"', '', $path) . '" > /dev/null 2>&1 &');
    }

    function slideShowDir($uri)
    {
        $path = $this->fs->realPath($uri);
        Shell::exec('killall eom');
        Shell::exec('export DISPLAY=:0.0 && eom -fs "' . str_replace('"', '', $path) . '" > /dev/null 2>&1 &');
    }
}