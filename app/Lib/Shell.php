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
    static function exec($str) {
        $file = storage_path('logs/shell_exec_' . date('Ymd') . '.log');
        file_put_contents($file, date('Y-m-d H:i:s ') . $str);
        return shell_exec($str);

    }
}