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
    private $charsets = array(
        'UTF-8' => 'UTF-8',
        'WINDOWS-1251' => 'WINDOWS-1251',
    );

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
        $charsetIn = isset($_GET['charset_in']) ? $_GET['charset_in'] : 'UTF-8';
        $charsetIn = isset($this->charsets[$charsetIn]) ? $charsetIn : 'UTF-8';
        $data = '';
        if (is_file($file)) {
            $data = file_get_contents($file);
            $data = iconv($charsetIn, 'UTF-8//IGNORE', $data);
        }
        return response($data, 200)
            ->header('Content-Type', 'text/plain');
    }

    function save(Request $request, $uri)
    {
        $file = $this->fs->realPath($uri);
        $charsetOut = isset($_POST['charset_out']) ? $_POST['charset_out'] : 'UTF-8';
        $charsetOut = isset($this->charsets[$charsetOut]) ? $charsetOut : 'UTF-8';
        $nl = isset($_POST['nl']) ? $_POST['nl'] : 'n';
        $nl = in_array($nl, ['n', 'rn', 'r']) ? $nl : 'n';
        $data = isset($_POST['data']) ? $_POST['data'] : '';
        if ($nl == 'n') {
            $data = str_replace("\r\n", "\n", $data);
            $data = str_replace("\r", '', $data);
        } elseif ($nl == 'r') {
            $data = str_replace("\r\n", "\r", $data);
            $data = str_replace("\n", '', $data);
        } elseif ($nl == 'rn') {
            $data = str_replace("\r", '', $data);
            $data = str_replace("\n", "\r\n", $data);
        }
        $data = iconv('UTF-8', $charsetOut . '//IGNORE', $data);
        $len = file_put_contents($file, $data);
        return response()->json(['len' => $len]);
    }

}