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
    static function system($str) {
        self::log($str);
        $r = system($str, $o);
        return [$str, $r, $o];
    }

    static function exec($str) {
        self::log($str);
        return shell_exec($str);
    }

    static function log($str) {
        $file = storage_path('logs/shell_exec_' . date('Ymd') . '.log');
        $str .= ";\t" . $_SERVER['REMOTE_ADDR'] . ";\t" . $_SERVER['REQUEST_URI'] . ";\t" . http_build_query($_POST) . "\n";
        file_put_contents($file, date('Y-m-d H:i:s ') . $str, FILE_APPEND);
    }
}