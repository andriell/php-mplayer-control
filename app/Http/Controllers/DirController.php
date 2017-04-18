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
        $dir = config('my.media_dir');
        $files = [];
        if ($handle = opendir($dir)) {
            while (false !== ($entry = readdir($handle))) {
                $files[] = array('name' => $entry);
            }
            closedir($handle);
        }
        return view('dir', [
            'files' => $files,
        ]);
    }
}