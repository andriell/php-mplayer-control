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
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;

class MPlayerController extends Controller
{
    /** @var MPlayer */
    private $player;

    /**
     * DirController constructor.
     * @param MPlayer $player
     */
    public function __construct(MPlayer $player)
    {
        $this->player = $player;
        $this->middleware('auth');
    }

    public function callAction($method, $parameters)
    {
        set_time_limit(5);
        return parent::callAction($method, $parameters);
    }


    public function playVideo($uri)
    {
        $this->player->playVideo($uri);
    }

    public function pause()
    {
        $this->player->pause();
    }

    public function quit()
    {
        $this->player->quit();
    }

    public function getVolume()
    {
        return response()->json(['volume' => $this->player->getVolume()]);
    }

    public function setVolume($volume)
    {
        $this->player->setVolume($volume);
    }

    public function getLength()
    {
        return response()->json(['length' => $this->player->getLength()]);
    }

    public function getTimePos()
    {
        return response()->json([
            'time_pos' => $this->player->getTimePos(),
            'length' => $this->player->getLength(),
        ]);
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

    /*public function command() {
        return response()->json(['resp' => $this->player->commandGet($_GET['exec'])]);
    }*/

    public function getAllProperty() {
        return response()->json(['resp' => $this->player->getAllProperty()]);
    }
}