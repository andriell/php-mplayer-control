<?php
/**
 * Created by PhpStorm.
 * User: Андрей
 * Date: 19.04.2017
 * Time: 17:40
 */

namespace App\Lib;


class MPlayer
{
    /** @var FileSystem */
    private $fs;

    function playFile($uri) {
        $file = $this->fs->realPath($uri);
        if (empty($file)) {
            return;
        }
        shell_exec('killall mplayer');
        $str = 'export DISPLAY=:0.0 && mplayer -really-quiet -noconsolecontrols -fs -slave -input file=/tmp/mplayer-fifo ' . $file . '  > /dev/null &';
        shell_exec($str);
    }
}