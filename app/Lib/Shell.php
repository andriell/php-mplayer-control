<?php
/**
 * Created by PhpStorm.
 * User: Андрей
 * Date: 25.04.2017
 * Time: 11:36
 */

namespace App\Lib;


class Shell
{
    static function exec($str, $log = true) {
        if ($log) {
            self::log($str);
        }
        return  iconv("UTF-8","UTF-8//IGNORE", shell_exec($str));
    }

    static function log($str) {
        $file = storage_path('logs/shell_exec_' . date('Ymd') . '.log');
        $str .= ";\t" . $_SERVER['REMOTE_ADDR'] . ";\t" . $_SERVER['REQUEST_URI'] . ";\t" . http_build_query($_POST) . "\n";
        file_put_contents($file, date('Y-m-d H:i:s ') . $str, FILE_APPEND);
    }
}