<?php
use App\Classes\Auth;
use App\Classes\Hash;
use App\Classes\User;
use App\Classes\Input;
use App\Classes\Token;
use App\Classes\Session;
use App\Classes\Redirect;
use App\Classes\Validate;

require "app/bootstrap.php";
include_once "includes/header.php";

$errors = null;


if (Auth::check()) {
    Redirect::to('index.php');
}


if (Input::check()) {
    if (Token::check(Input::get('_token'))) {
        $validate = new Validate;
        $validation = $validate->validation($_POST, [
            'name' => [
                'required' => true,
                'name' => 'نام'
            ],
            'username' => [
                'required' => true,
                'unique' => 'users',
                'name' => 'نام کاربری'
            ],
            'email' => [
                'required' => true,
                'unique' => 'users',
                'name' => 'ایمیل'
            ],
            'password' => [
                'required' => true,
                'min' => '8',
                'matches' => 'password_confirmation',
                'name' => 'رمز عبور'
            ],
        ]);
        if ($validation->passed()) {
            $salt = Hash::salt(32);
            if (!Auth::register([
                'name' => escape(Input::get('name')),
                'username' => escape(Input::get('username')),
                'email' => escape(Input::get('email')),
                'password' => Hash::make(Input::get('password'), $salt),
                'salt' => $salt,
                'created_at' => date('Y-m-d H:i:s'),
            ])) {
                $errors = ['هنگام افزودن اطلاعات مشکلی پیش آمده.'];
            } else {
                Redirect::to('index.php');
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
                    <label for="name">نام</label>
                    <input type="text" class="form-control" id="name" name="name" placeholder="نام خود را وارد کنید" value="<?= escape(Input::get('name')) ?>">
                </div>
                <div class="form-group">
                    <label for="username">نام کاربری</label>
                    <input type="text" class="form-control" id="username" name="username" placeholder="نام کاربری خود را وارد کنید" value="<?= escape(Input::get('username')) ?>">
                </div>
                <div class="form-group">
                    <label for="email">ایمیل</label>
                    <input type="email" class="form-control" id="email" name="email" placeholder="ایمیل خود را وارد کنید" value="<?= escape(Input::get('email')) ?>">
                </div>
                <div class="form-group">
                    <label for="password">رمز عبور</label>
                    <input type="password" class="form-control" id="password" name="password" placeholder="رمز عبور خود را وارد کنید">
                </div>
                <div class="form-group">
                    <label for="password_confirmation">تکرار رمز عبور</label>
                    <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" placeholder="تکرار رمز عبور">
                </div>
                <button type="submit" class="btn btn-outline-primary w-100 mt-3">ثبت نام</button>
                <input type="hidden" name="_token" value="<?= Token::generate() ?>">
            </form>
        </div>
    </div>
</div>

<?php 

include_once "includes/footer.php";
?>