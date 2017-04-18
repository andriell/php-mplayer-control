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

    function getList() {
        $uri = '/' . trim(Input::get('uri', ''), '/');
        $dir = realpath(config('my.media_dir') . $uri);
        $r = [
            'uri' => $uri,
            'items' => []
        ];
        if (strpos($dir, config('my.media_dir')) !== 0) {
            return response()->json($r);
        }
        foreach ([true, false] as $isDir) {
            if ($handle = opendir($dir)) {
                while (false !== ($entry = readdir($handle))) {
                    if ($entry == '.' || $entry == '..') {
                        continue;
                    }
                    if ($isDir xor is_dir($dir . '\\' . $entry)) {
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
}