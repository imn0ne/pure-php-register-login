<?php

namespace App\Classes;

use App\Database\DB;
use App\Classes\User;

class Auth
{
    public static function attempt($username, $password, $remmember = false)
    {
        $user = new User;
        return $user->login($username, $password, $remmember);
    }

    public static function check()
    {
        $user = new User;
        return $user->isLoggedIn();
    }

    public static function register($fields)
    {
        $user = new User;
        return $user->register($fields);
    }

    public static function user()
    {
        if (self::check()) {
            $user = new User;
            return $user->data();
        }
        return null;
    }
}