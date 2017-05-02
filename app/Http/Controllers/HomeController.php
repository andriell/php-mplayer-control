<?php

namespace App\Http\Controllers;

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
        $data = [];
        $data['disc_total'] = disk_total_space(config('nas.media_dir'));
        $data['disc_total_f'] = round($data['disc_total'] / (1024 * 1024)) / 1000 . ' Гб';
        $data['disc_used'] = $data['disc_total'] - disk_free_space(config('nas.media_dir'));
        $data['disc_used_f'] = round($data['disc_used'] / (1024 * 1024)) / 1000 . ' Гб';
        $data['disc_p'] = round($data['disc_used'] / $data['disc_total'] * 10000) / 100;

        $data['system_total'] = disk_total_space(config('nas.system_dir'));
        $data['system_total_f'] = round($data['system_total'] / (1024 * 1024)) / 1000 . ' Гб';
        $data['system_used'] = $data['system_total'] - disk_free_space(config('nas.system_dir'));
        $data['system_used_f'] = round($data['system_used'] / (1024 * 1024)) / 1000 . ' Гб';
        $data['system_p'] = round($data['system_used'] / $data['system_total'] * 10000) / 100;
        
        return view('home', $data);
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
