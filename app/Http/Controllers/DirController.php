<?php
/**
 * Created by PhpStorm.
 * User: Андрей
 * Date: 18.04.2017
 * Time: 8:56
 */

namespace App\Http\Controllers;


use App\Lib\FileSystem;
use Illuminate\Support\Facades\Input;

class DirController extends Controller
{

    private $fs;

    /**
     * DirController constructor.
     * @param FileSystem $fs
     */
    public function __construct(FileSystem $fs)
    {
        $this->fs = $fs;
    }

    function index()
    {
        return view('dir');
    }

    private function realPath()
    {
        $uri = '/' . trim(Input::get('uri', ''), '/');
        $dir = realpath(config('nas.media_dir') . $uri);
        if (strpos($dir, config('nas.media_dir')) === 0) {
            return [$dir, $uri];
        }
        return false;
    }

    function getList()
    {
        return response()->json($this->fs->readDir(Input::get('uri')));
    }

    function img()
    {
        return response()->stream(function() {
            $this->fs->resizeImage(Input::get('uri'));
        }, 200, [
            'Content-Type' => 'image/jpeg'
        ]);
    }

    function download()
    {
        $file = $this->fs->realPath(Input::get('uri'));
        return response()->download($file);
    }
}