<?php
/**
 * Created by PhpStorm.
 * User: Андрей
 * Date: 19.04.2017
 * Time: 17:40
 */

namespace App\Lib;


class XBoTool
{

    function key($key)
    {
        Shell::exec('export DISPLAY=:0.0 && xdotool key "' . str_replace('"', '', $key) . '"');
    }

    function keyLeft()
    {
        $this->key('Left');
    }

    function keyRight()
    {
        $this->key('Right');
    }
}