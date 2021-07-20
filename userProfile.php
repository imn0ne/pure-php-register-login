<?php
require "app/bootstrap.php";
include_once "includes/header.php";

use App\Classes\Auth;
use App\Classes\User;
use App\Classes\Input;
use App\Classes\Token;
use App\Classes\Redirect;
use App\Classes\Validate;

if (!Auth::check()) {
    Redirect::to('index.php');
}

$errors = null;
$success = null;

$user = new User;

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
                'name' => 'نام کاربری'
            ],
            'email' => [
                'required' => true,
                'name' => 'ایمیل'
            ],
        ]);
        if ($validation->passed()) {
            if (!$user->update([
                'name' => escape(Input::get('name')),
                'username' => escape(Input::get('username')),
                'email' => escape(Input::get('email')),
            ])) {
                $errors = ['هنگام افزودن اطلاعات مشکلی پیش آمده.'];
            } else {
                $user = new User;
                $success = "با موفقیت انجام شد.";
            }
        } else {
            $errors = $validation->errors();
        }
    }
}

?>

<div class="container">
    <div class="row">
        <div class="col-md-8 mx-auto user-profile">
            <h3>اطلاعات شخصی</h3>
            <hr>
            <div class="row">
                <div class="col-md-4">
                    <ul>
                        <li><a href="changePassword.php">تغییر رمز عبور</a></li>
                    </ul>
                </div>
                <div class="col-md-8">
                    <form action="" method="post">
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
                        <?php elseif ($success): ?>
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                <?= $success ?>
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                        <?php endif; ?>  
                        <div class="form-group">
                            <label for="name">نام</label>
                            <input type="text" class="form-control" id="name" name="name" placeholder="نام" value="<?= $user->data()->name ?>">
                        </div>
                        <div class="form-group">
                            <label for="username">نام کاربری</label>
                            <input type="text" class="form-control" id="username" name="username" placeholder="نام کاربری" value="<?= $user->data()->username ?>">
                        </div>
                        <div class="form-group">
                            <label for="email">ایمیل</label>
                            <input type="email" class="form-control" id="email" name="email" placeholder="ایمیل" value="<?= $user->data()->email ?>">
                        </div>
                        <button type="submit" class="btn btn-outline-primary w-100 mt-3">ویرایش</button>
                        <input type="hidden" name="_token" value="<?= Token::generate() ?>">
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
include_once "includes/footer.php";
?>
