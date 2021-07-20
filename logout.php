<?php

require_once "app/bootstrap.php";

use App\Classes\User;
use App\Classes\Redirect;

$user = new User;

$user->logout();
Redirect::to('index.php');