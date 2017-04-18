<?php
/**
 * Created by PhpStorm.
 * User: Андрей
 * Date: 18.04.2017
 * Time: 8:56
 */

namespace App\Http\Controllers;


use Illuminate\Support\Facades\Input;

class DirController extends Controller
{
    function index() {
        return view('dir');
    }

    private function realPath() {
        $uri = '/' . trim(Input::get('uri', ''), '/');
        $dir = realpath(config('my.media_dir') . $uri);
        if (strpos($dir, config('my.media_dir')) === 0) {
            return [$dir, $uri];
        }
        return false;
    }

    function getList() {
        $dir = $this->realPath();
        $r = [
            'uri' => '/',
            'items' => []
        ];
        if (!$dir || !is_dir($dir[0])) {
            return response()->json($r);
        }
        $r['uri'] = $dir[1];
        foreach ([true, false] as $isDir) {
            if ($handle = opendir($dir[0])) {
                while (false !== ($entry = readdir($handle))) {
                    if ($entry == '.' || $entry == '..') {
                        continue;
                    }
                    if ($isDir xor is_dir($dir[0] . '\\' . $entry)) {
                        continue;
                    }
                    $r['items'][] = array(
                        'name' => $entry,
                        'is_dir' => $isDir,
                    );
                }
                closedir($handle);
            }
        }
        return response()->json($r);
    }

    function download() {
        $file = $this->realPath();
        if ($file && file_exists($file[0])) {
            header('Content-Description: File Transfer');
            header('Content-Type: application/octet-stream');
            header('Content-Disposition: attachment; filename="'.basename($file[0]).'"');
            header('Expires: 0');
            header('Cache-Control: must-revalidate');
            header('Pragma: public');
            header('Content-Length: ' . filesize($file[0]));
            readfile($file[0]);
        }
    }
}