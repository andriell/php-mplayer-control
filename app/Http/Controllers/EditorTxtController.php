<?php
/**
 * Created by PhpStorm.
 * User: Андрей
 * Date: 18.04.2017
 * Time: 8:56
 */

namespace App\Http\Controllers;

use App\Lib\Eom;
use App\Lib\FileSystem;
use App\Lib\ImageMagick;
use App\Lib\XBoTool;
use Illuminate\Http\Request;

class EditorTxtController extends Controller
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

    function load(Request $request, $uri = '')
    {
        $file = $this->fs->realPath($uri);
        $data = '';
        if (is_file($file)) {
            $data = file_get_contents($file);
            $data = iconv($_GET['charset_in'],'UTF-8//IGNORE', $data);
        }
        return response($data, 200)
            ->header('Content-Type', 'text/plain');
    }

    private function save(Request $request, $uri)
    {
        $file = $this->fs->realPath($uri);
        if (is_file($file)) {
            $data = $_POST['data'];
            $data = iconv('UTF-8', $_POST['charset_in'], $data);
            file_put_contents($file, $data);
        }

    }

}