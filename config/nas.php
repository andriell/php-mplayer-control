<?php
/**
 * Created by PhpStorm.
 * User: Андрей
 * Date: 18.04.2017
 * Time: 12:03
 */

return [
    'system_dir' => env('NAS_SYSTEM_DIR', '/'),
    'media_dir' => env('NAS_MEDIA_DIR', '/home/data'),
    'yandex_dir' => env('NAS_YANDEX_DIR', false),
    'yandex_size' => env('NAS_YANDEX_SIZE', 0),
    'file_system_encoding' => env('NAS_FILE_SYSTEM_ENCODING', false),
    'transmission_url' => env('NAS_TRANSMISSION_URL', 'http://localhost:9091/transmission/rpc'),
    'transmission_user' => env('NAS_TRANSMISSION_USER', 'user'),
    'transmission_password' => env('NAS_TRANSMISSION_PASSWORD', 'password'),
    'home_ip_start' => env('NAS_HOME_IP_START', false),
];