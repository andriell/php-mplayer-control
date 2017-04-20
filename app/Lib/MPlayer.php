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
    private $fileFifo = '/dev/shm/mplayer-fifo';
    private $fileOut = '/dev/shm/mplayer-out';

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
        shell_exec('rm ' . $this->fileFifo);
        shell_exec('mkfifo ' . $this->fileFifo . ' -m 0644');
        $str = 'export DISPLAY=:0.0 && mplayer -quiet -fs -slave -input file=' . $this->fileFifo . ' ' . $file . '  > ' . $this->fileOut . ' 2> /dev/null &';
        //$str = 'export DISPLAY=:0.0 && mplayer -really-quiet -noconsolecontrols -fs -slave -input file=' . $this->fileFifo . ' ' . $file . '  > ' . $this->fileOut . ' 2> /dev/null &';
        shell_exec($str);
        shell_exec('chmod 0644 ' . $this->fileOut);
    }

    function isRun() {
        return (bool) shell_exec('pidof mplayer');
    }

    function command($str) {
        shell_exec('echo "' . $str . '\n" > ' . $this->fileFifo);
    }

    function commandGet($str) {
        shell_exec('> ' . $this->fileOut);
        shell_exec('echo "' . $str . '\n" > ' . $this->fileFifo);
        $r = '';
        for ($i = 0; $i < 10; $i++) {
            $r = trim(file_get_contents($this->fileOut));
            if ($r) {
                break;
            }
            sleep(100);
        }
        return $r;
    }

    function pause() {
        $this->command('pause');
    }

    function quit() {
        $this->command('quit');
    }
}