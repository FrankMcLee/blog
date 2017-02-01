<?php
/**
 * Created by PhpStorm.
 * User: Frank
 * Date: 01/02/2017
 * Time: 5:29 PM
 */
function get_db_config()
{
    if (getenv('IS_IN_HEROKU')) {
        $url = parse_url(getenv('DATABASE_URL'));

        $db_config = [
            'connection' => 'pgsql',
            'host' => $url['host'],
            'database' => substr($url['path'], 1),
            'username' => $url['user'],
            'password' => $url['pass'],
        ];
    } else {
        $db_config = [
            'connection' => env('DB_CONNECTION', 'mysql'),
            'host' => env('DB_HOST', 'localhost'),
            'database' => env('DB_DATABASE', 'forge'),
            'username' => env('DB_USERNAME', 'forge'),
            'password' => env('DB_PASSWORD', ''),
        ];
    }

    return $db_config;
}