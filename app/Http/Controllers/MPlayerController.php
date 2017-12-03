<?php
/**
 * Created by PhpStorm.
 * User: Андрей
 * Date: 18.04.2017
 * Time: 8:56
 */

namespace App\Http\Controllers;


use App\Lib\FileSystem;
use App\Lib\MPlayer;
use App\Lib\StackFile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;

class MPlayerController extends Controller
{
    /** @var  FileSystem */
    private $fs;
    /** @var MPlayer */
    private $player;
    /** @var StackFile */
    private $stackFile;

    /**
     * DirController constructor.
     * @param FileSystem $fs
     * @param MPlayer $player
     * @param StackFile $stackFile
     */
    public function __construct(FileSystem $fs, MPlayer $player, StackFile $stackFile)
    {
        $this->fs = $fs;
        $this->player = $player;
        $this->stackFile = $stackFile;
        $this->middleware('auth');
    }

    public function callAction($method, $parameters)
    {
        set_time_limit(5);
        return parent::callAction($method, $parameters);
    }


    public function playVideo($uri)
    {
        $this->stackFile->add($uri);
        $this->player->playVideo($uri);
        $this->stackFile->save();
    }

    public function playNextVideo($uri)
    {
        $fileName = basename($this->fs->realPath($uri));
        $baseUri = substr($uri, 0, strlen($uri) - strlen($fileName));
        $list = $this->fs->readDir($baseUri, ['type' => ['movie']], ['name']);
        $returnNext = false;
        foreach ($list['items'] as $row) {
            if ($returnNext) {
                $this->playVideo($baseUri . $row['name']);
                break;
            }
            if ($row['name'] == $fileName) {
                $returnNext = true;
            }
        }
    }

    public function pause()
    {
        $this->player->pause();
        return response()->json($this->player->getInfo([
            'pause',
            'time_pos',
        ]));
    }

    public function stepTimePos($timePos)
    {
        $this->player->stepTimePos($timePos);
    }

    public function quit()
    {
        $this->player->quit();
    }

    public function setVolume($volume)
    {
        $this->player->setVolume($volume);
    }


    public function getTimePos()
    {
        return response()->json($this->player->getInfo([
            'length',
            'time_pos',
        ]));
    }

    public function getLastFile()
    {
        return response()->json($this->stackFile->getData());
    }

    public function setTimePos($timePos)
    {
        $this->player->setTimePos($timePos);
    }

    public function setMute($mute)
    {
        $this->player->setMute($mute == 't');
    }

    public function switchAudio()
    {
        $this->player->switchAudio();
    }

    public function switchVideo()
    {
        $this->player->switchVideo();
    }

    public function command()
    {
        return response()->json(['resp' => $this->player->commandGet($_GET['exec'])]);
    }

    public function getAllProperty()
    {
        return response()->json(['resp' => $this->player->getAllProperty()]);
    }

    public function getInfo()
    {
        $r = $this->player->getInfo([
            'filename',
            'length',
            'mute',
            'time_pos',
            'volume',
            'pause',
        ]);
        $r['last_file'] = $this->stackFile->getData();
        return response()->json($r);
    }
}