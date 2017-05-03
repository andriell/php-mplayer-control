<?php
/**
 * Created by PhpStorm.
 * User: Андрей
 * Date: 03.05.2017
 * Time: 11:56
 */

namespace App\Lib;


class Decorator
{
    const DATE_FORMAT = 'Y-m-d H:i:s';

    static function date($time) {
        return date(self::DATE_FORMAT, $time);
    }

    static function size($size, $precision = 2) {
        if ($size > 1099511627776) {
            return round($size / 1099511627776, $precision) . ' Тб';
        } elseif ($size > 1073741824) {
            return round($size / 1073741824, $precision) . ' Гб';
        } elseif ($size > 1048576) {
            return round($size / 1048576, $precision) . ' Мб';
        } elseif ($size > 1024) {
            return round($size / 1024, $precision) . ' Кб';
        } else {
            return $size . ' b';
        }
    }
}