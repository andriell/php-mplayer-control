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
        $headers = ['Content-Type' => 'image/jpeg'];
        //<editor-fold desc="http cache">
        $serverDate = $this->fs->fileMTime($uri);
        if ($serverDate) {
            $headers['Last-modified'] = gmdate("D, d M Y H:i:s \G\M\T", $serverDate);
            $userDate = $request->headers->get('if-modified-since', false);
            if ($userDate) {
                $userDate = strtotime($userDate);
                if ($userDate == strtotime(gmdate("D, d M Y H:i:s \G\M\T", $serverDate))) {
                    return response(null, 304, $headers);
                }
            }
        }
        //</editor-fold>
        return response()->stream(function() use($uri) {
            $this->fs->resizeImage($uri);
        }, 200, $headers);
    }

    function download(Request $request, $uri)
    {
        $file = $this->fs->realPath($uri);
        return response()->download($file);
    }

    function mv()
    {
        return response()->json(['status' => $this->fs->fileRename($_POST['uri_dir'], $_POST['old_name'], $_POST['new_name'])]);
    }

    function cut()
    {
        return response()->json(['status' => $this->fs->mvBackupNumbered($_POST['uri_from'], $_POST['uri_to'])]);
    }

    function copy()
    {
        return response()->json(['status' => $this->fs->cpBackupNumbered($_POST['uri_from'], $_POST['uri_to'])]);
    }

    function onlyDir(Request $request, $uri = '') {
        $r = [];
        $list = $this->fs->readDir($uri, ['name'], ['only_dir' => true]);
        if (empty($list)) {
            return response()->json($r);
        }
        foreach ($list['items'] as $item) {
            $r[] = $item['name'];
        }
        return response()->json($r);
    }
}