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

    function playFile($uri)
    {
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

    function isRun()
    {
        return (bool)shell_exec('pidof mplayer');
    }

    function command($str)
    {
        shell_exec('echo "' . $str . '\n" > ' . $this->fileFifo);
    }

    function commandGet($str)
    {
        shell_exec('> ' . $this->fileOut);
        shell_exec('echo "' . $str . '\n" > ' . $this->fileFifo);
        $r = '';
        for ($i = 0; $i < 20; $i++) {
            $r = shell_exec('cat ' . $this->fileOut . ' | tr -d " \t\n\r\0"');
            if ($r) {
                break;
            }
            usleep(100000);
        }
        return $r;
    }

    function getAllProperty()
    {
        $prop = [
            'osdlevel', 'speed', 'loop', 'pause', 'filename', 'path', 'demuxer', 'stream_pos', 'stream_start',
            'stream_end', 'stream_length', 'stream_time_pos', 'titles', 'chapter', 'chapters', 'angle', 'length',
            'percent_pos', 'time_pos', 'metadata', 'metadata/*', 'volume', 'balance', 'mute', 'audio_delay',
            'audio_format', 'audio_codec', 'audio_bitrate', 'samplerate', 'channels', 'switch_audio', 'switch_angle',
            'switch_title', 'capturing', 'fullscreen', 'deinterlace', 'ontop', 'rootwin', 'border', 'framedropping',
            'gamma', 'brightness', 'contrast', 'saturation', 'hue', 'panscan', 'vsync', 'video_format', 'video_codec',
            'video_bitrate', 'width', 'height', 'fps', 'aspect', 'switch_video', 'switch_program', 'sub', 'sub_source',
            'sub_file', 'sub_vob', 'sub_demux', 'sub_delay', 'sub_pos', 'sub_alignment', 'sub_visibility',
            'sub_forced_only', 'sub_scale', 'tv_brightness', 'tv_contrast', 'tv_saturation', 'tv_hue', 'teletext_page',
            'teletext_subpage', 'teletext_mode', 'teletext_format', 'teletext_half_page'
        ];
        $r = [];
        foreach ($prop as $p) {
            $r[$p] = $this->commandGet('get_property ' . $p);
        }
        return $r;
    }

    function pause()
    {
        $this->command('pause');
    }

    function quit()
    {
        $this->command('quit');
    }
}