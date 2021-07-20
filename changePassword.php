<?php
require "app/bootstrap.php";
include_once "includes/header.php";

use App\Classes\Auth;
use App\Classes\Hash;
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
            'current_password' => [
                'required' => true,
                'name' => 'رمز عبور قدیمی'
            ],
            'new_password' => [
                'required' => true,
                'min' => 8,
                'matches' => 'new_password_confirmation',
                'name' => 'رمز عبور جدید'
            ],
        ]);
        if ($validation->passed()) {
            if ($user->data()->password === Hash::make(Input::get('current_password'), $user->data()->salt)) {
                $salt = Hash::salt(32);
                if (!$user->update([
                    'password' => escape(Hash::make(Input::get('new_password'), $salt)),
                    'salt' => $salt,
                ])) {
                    $errors = ['هنگام افزودن اطلاعات مشکلی پیش آمده.'];
                } else {
                    $success = "با موفقیت انجام شد.";
                }
            } else {
                $errors = ['رمز عبور قبلی وارد شده معتبر نمی باشد.'];
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
            <h3>تغییر رمز عبور</h3>
            <hr>
            <div class="row">
                <div class="col-md-4">
                    <ul>
                        <li><a href="userProfile.php">اطلاعات کاربری</a></li>
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
                            <label for="current_password">رمز عبور قدیمی</label>
                            <input type="password" class="form-control" id="current_password" name="current_password" placeholder="رمز عبور قدیمی">
                        </div>
                        <div class="form-group">
                            <label for="new_password">رمز عبور جدید</label>
                            <input type="password" class="form-control" id="new_password" name="new_password" placeholder="رمز عبور جدید" >
                        </div>
                        <div class="form-group">
                            <label for="new_password_confirmation">تکرار رمز عبور جدید</label>
                            <input type="password" class="form-control" id="new_password_confirmation" name="new_password_confirmation" placeholder="تکرار رمز عبور جدید">
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
