<?php
/**
 * Created by PhpStorm.
 * User: Андрей
 * Date: 19.04.2017
 * Time: 7:52
 */

namespace App\Providers;


use App\Lib\Eom;
use App\Lib\FileSystem;
use App\Lib\FileSystemOverride;
use App\Lib\Image;
use App\Lib\MPlayer;
use App\Lib\Transmission\RPC;
use App\Lib\XBoTool;
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
        $this->app->singleton(FileSystemOverride::class, function ($app) {
            /** @var $app \Illuminate\Foundation\Application */
            return new FileSystemOverride(config('nas.file_system_encoding'));
        });
        $this->app->singleton(FileSystem::class, function ($app) {
            /** @var $app \Illuminate\Foundation\Application */
            return new FileSystem(config('nas.media_dir'), $app->make(FileSystemOverride::class));
        });
        $this->app->singleton(Eom::class, function ($app) {
            /** @var $app \Illuminate\Foundation\Application */
            return new Eom($app->make(FileSystem::class));
        });
        $this->app->singleton(Image::class, function ($app) {
            /** @var $app \Illuminate\Foundation\Application */
            return new Image($app->make(FileSystem::class), $app->make(FileSystemOverride::class));
        });
        $this->app->singleton(MPlayer::class, function ($app) {
            /** @var $app \Illuminate\Foundation\Application */
            return new MPlayer($app->make(FileSystem::class));
        });
        $this->app->singleton(XBoTool::class, function ($app) {
            /** @var $app \Illuminate\Foundation\Application */
            return new XBoTool();
        });
        $this->app->singleton(RPC::class, function ($app) {
            /** @var $app \Illuminate\Foundation\Application */
            return new RPC(config('nas.transmission_url'), config('nas.transmission_user'), config('nas.transmission_password'), true);
        });
    }
}