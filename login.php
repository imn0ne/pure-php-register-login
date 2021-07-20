<?php

use App\Classes\Auth;
use App\Classes\Input;
use App\Classes\Token;
use App\Classes\Redirect;
use App\Classes\Validate;


require "app/bootstrap.php";
include_once "includes/header.php";

if (Auth::check()) {
    Redirect::to('index.php');
}

$errors = null;

if (Input::check()) {
    if (Token::check(Input::get('_token'))) {
        $validate = new Validate;
        $validation = $validate->validation($_POST, [
            'username' => [
                'required' => true,
                'name' => 'نام کاربری'
            ],
            'password' => [
                'required' => true,
                'name' => 'رمز عبور'
            ],
        ]);
        if ($validation->passed()) {
            if (Auth::attempt(escape(Input::get('username')), escape(Input::get('password')), escape(Input::get('remember')))) {
                Redirect::to('index.php');
            } else {
                $errors = ['نام کاربری یا رمز عبور شما معتبر نمی باشد.'];
            }
        } else {
            $errors = $validation->errors();
        }
    }
}

?>

<div class="container mt-5">
    <div class="row">
        <div class="col-md-5 mx-auto panel">
            <form action="" method="post" class="">
                <?php if ($errors): ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <ul>
                            <?php foreach ($errors as $error): ?>
                                <li><?= $error ?></li>
                            <?php endforeach; ?>
                        </ul>
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                <?php endif; ?>  
                <div class="form-group">
                    <label for="username">نام کاربری</label>
                    <input type="text" class="form-control" id="username" name="username" placeholder="نام کاربری خود را وارد کنید">
                </div>
                <div class="form-group">
                    <label for="password">رمز عبور</label>
                    <input type="password" class="form-control" id="password" name="password" placeholder="رمز عبور خود را وارد کنید">
                </div>
                <div class="form-group form-check">
                    <input type="checkbox" class="form-check-input" id="remember" name="remember">
                    <label class="form-check-label mr-4" for="remember">مرا به خاطر بسپار</label>
                </div>
                <button type="submit" class="btn btn-outline-primary w-100 mt-3">ورود</button>
                <input type="hidden" name="_token" value="<?= Token::generate() ?>">
            </form>
        </div>
    </div>
</div>

<?php 

include_once "includes/footer.php";
?>