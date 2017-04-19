<?php
/**
 * Created by PhpStorm.
 * User: Андрей
 * Date: 19.04.2017
 * Time: 7:52
 */

namespace App\Providers;


use App\Lib\FileSystem;
use App\Lib\FileSystemOverride;
use App\Lib\MPlayer;
use Illuminate\Support\ServiceProvider;

class LibProvider extends ServiceProvider
{

    /**
     * Регистрация привязка в контейнере.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(FileSystem::class, function ($app) {
            return new FileSystem(config('nas.media_dir'), new FileSystemOverride(config('nas.file_system_encoding')));
        });
        $this->app->singleton(MPlayer::class, function ($app) {
            return new MPlayer(new FileSystem(config('nas.media_dir'), new FileSystemOverride(config('nas.file_system_encoding'))));
        });
    }
}