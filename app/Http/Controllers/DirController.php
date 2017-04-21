<?php
/**
 * Created by PhpStorm.
 * User: Андрей
 * Date: 18.04.2017
 * Time: 8:56
 */

namespace App\Http\Controllers;


use App\Lib\FileSystem;
use Illuminate\Http\Request;
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
        $this->middleware('auth');
    }

    function index()
    {
        return view('dir');
    }


    function getList(Request $request, $uri = '')
    {
        return response()->json($this->fs->readDir($uri));
    }

    function img(Request $request, $uri)
    {
        return response()->stream(function() use($uri) {
            $this->fs->resizeImage($uri);
        }, 200, [
            'Content-Type' => 'image/jpeg'
        ]);
    }

    function download(Request $request, $uri)
    {
        $file = $this->fs->realPath($uri);
        return response()->download($file);
    }
}