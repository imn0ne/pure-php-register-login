<?php

use App\Database\DB;
use App\Classes\User;
use App\Classes\Cookie;
session_start();

require_once realpath("vendor/autoload.php");
require_once realpath("config/app.php");
require_once realpath("functions/sanitize.php");



if (Cookie::has('hash')) {
    if (!DB::getInstance()->find('users_session', ['hash' , '=', Cookie::get('hash')])->error()) {
        $user = DB::getInstance()->find('users_session', ['hash' , '=', Cookie::get('hash')])->first();
        
        $user = new User($user->user_id);

        $user->login();
    }
}