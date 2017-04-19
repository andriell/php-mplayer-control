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
        // тип содержимого
        header('Content-Type: image/jpeg');

        return $this->fs->resizeImage(Input::get('uri'));
    }

    function download()
    {
        $file = $this->realPath();
        if ($file && file_exists($file[0])) {
            header('Content-Description: File Transfer');
            header('Content-Type: application/octet-stream');
            header('Content-Disposition: attachment; filename="' . basename($file[0]) . '"');
            header('Expires: 0');
            header('Cache-Control: must-revalidate');
            header('Pragma: public');
            header('Content-Length: ' . filesize($file[0]));
            readfile($file[0]);
        }
    }
}