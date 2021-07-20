<?php 

namespace App\Classes;

class Hash
{
    public static function make($string, $salt = '')
    {
        return hash('sha256', $string . $salt);
    }

    public static function salt($lentgh)
    {
        return utf8_decode(random_bytes($lentgh));
    }

    public static function unique()
    {
        return self::make(uniqid());
    }
}