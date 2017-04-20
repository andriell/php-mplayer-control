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

    public function playFile()
    {
        $files = $_POST['files'];
        $this->player->playFile($files[0]);
    }

    public function pause()
    {
        $this->player->pause();
    }

    public function quit()
    {
        $this->player->quit();
    }

    public function command() {
        return response()->json(['resp' => $this->player->commandGet($_GET['exec'])]);
    }

    public function getAllProperty() {
        return response()->json(['resp' => $this->player->getAllProperty()]);
    }
}