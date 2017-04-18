<?php
/**
 * Created by PhpStorm.
 * User: Андрей
 * Date: 18.04.2017
 * Time: 8:56
 */

namespace App\Http\Controllers;


class DirController extends Controller
{
    function index() {
        return view('dir');
    }

    function getList() {
        $dir = config('my.media_dir');
        $r = [
            'path' => '/',
            'items' => []
        ];
        if ($handle = opendir($dir)) {
            while (false !== ($entry = readdir($handle))) {
                $r['items'][] = array('name' => $entry);
            }
            closedir($handle);
        }
        return response()->json($r);
    }
}