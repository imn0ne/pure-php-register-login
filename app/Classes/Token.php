<?php

namespace App\Classes;

use App\Classes\Hash;

class Token
{
    public static function generate()
    {
        return Session::put('_token', md5(uniqid()));
    }

    public static function check($token)
    {
        $tokenName = '_token';
        if (Session::has($tokenName) && $token === Session::get($tokenName)) {
            Session::delete($tokenName);
            return true;
        }
        return false;
    }
}