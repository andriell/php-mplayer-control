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
use Illuminate\Support\ServiceProvider;

class FileSystemProvider extends ServiceProvider
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
    }
}