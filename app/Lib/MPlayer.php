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

    /**
     * MPlayer constructor.
     * @param FileSystem $fs
     */
    public function __construct(FileSystem $fs)
    {
        $this->fs = $fs;
    }

    function playFile($uri) {
        $file = $this->fs->realPath($uri);
        if (empty($file)) {
            return;
        }
        shell_exec('killall mplayer');
        $str = 'export DISPLAY=:0.0 && mplayer -really-quiet -noconsolecontrols -fs -slave -input file=/tmp/mplayer-fifo ' . $file . '  > /dev/null &';
        shell_exec($str);
    }

    function isRun() {
        return (bool) shell_exec('pidof mplayer');
    }

    function command($str) {
        return shell_exec('echo "' . $str . '\n" > /tmp/mplayer-fifo');
    }

    function pause() {
        $this->command('pause');
    }

    function quit() {
        $this->command('quit');
    }
}