<?php

namespace App\Http\Controllers;

use App\Lib\Decorator;
use App\Lib\Shell;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    function formatBytes($bytes) {
        $units = array('B', 'KB', 'MB', 'GB', 'TB');



        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);

        // Uncomment one of the following alternatives
        // $bytes /= pow(1024, $pow);
        // $bytes /= (1 << (10 * $pow));

        return round($bytes, 2) . ' ' . $units[$pow];
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = ['title' => 'MyNAS'];
        $data['disc_total'] = disk_total_space(config('nas.media_dir'));
        $data['disc_total_f'] = Decorator::sizeGb($data['disc_total']);
        $data['disc_used'] = $data['disc_total'] - disk_free_space(config('nas.media_dir'));
        $data['disc_used_f'] = Decorator::sizeGb($data['disc_used']);
        $data['disc_p'] = round($data['disc_used'] / $data['disc_total'] * 100, 2);

        $data['system_total'] = disk_total_space(config('nas.system_dir'));
        $data['system_total_f'] = Decorator::sizeGb($data['system_total']);
        $data['system_used'] = $data['system_total'] - disk_free_space(config('nas.system_dir'));
        $data['system_used_f'] = Decorator::sizeGb($data['system_used']);
        $data['system_p'] = round($data['system_used'] / $data['system_total'] * 100, 2);

        $data['yandex_total'] = (int) config('nas.yandex_size');
        if ($data['yandex_total'] > 0) {
            $yandexDir = config('nas.yandex_dir');
            $data['yandex_total_f'] = Decorator::sizeGb($data['yandex_total']);
            $data['yandex_used'] = (int) Shell::exec('du -sLB1 --exclude=' . $yandexDir . '/.sync/* ' . $yandexDir, false);
            $data['yandex_used_f'] = Decorator::sizeGb($data['yandex_used']);
            $data['yandex_p'] = round($data['yandex_used'] / $data['yandex_total'] * 100, 2);
        }

        if (function_exists('sys_getloadavg')) {
            $var = sys_getloadavg();
        }

        return view('home', $data);
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function yandexDiskStatus()
    {
        $status = Shell::exec('yandex-disk status', false);
        return response()->json(['status' => $status]);
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function info()
    {
        ini_set('post_max_size', '500M');
        phpinfo();
    }
}
