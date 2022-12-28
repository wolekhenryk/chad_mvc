<?php

namespace app\core;

require_once __DIR__.'/../vendor/mongodb/mongodb/src/Client.php';

class DB
{
    public static $db = null;

    public static function get() {
        if (!isset(static::$db)) {
            static::$db =  new \MongoDB\Client('mongodb://127.0.0.1/wai', [
                'username' => 'wai_web',
                'password' => 'w@i_w3b'
            ]);
        }
        return static::$db->wai;
    }
}