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
    'file_system_encoding' => env('NAS_FILE_SYSTEM_ENCODING', false),
    'transmission_url' => env('NAS_TRANSMISSION_URL', 'http://localhost:9091/transmission/rpc'),
    'transmission_user' => env('NAS_TRANSMISSION_USER', 'user'),
    'transmission_password' => env('NAS_TRANSMISSION_PASSWORD', 'password'),
];