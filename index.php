<?php
require "app/bootstrap.php";
include_once "includes/header.php";
use App\Classes\Auth;
if (!Auth::check()):
?>
    <div class="container">
        <div class="col-md-6 mx-auto">
            <p class="" style="margin-top: 10rem; font-weight: 600; font-size: 1.2rem;">اگه هنوز ثبت نام نکردی متونی همین الان <a href="register.php">ثبت نام</a> کنی، واگه ثبت نام کردی <a href="login.php">وارد</a> حساب خودت شی.</p>
        </div>
    </div>
<?php else: ?>
    <div class="container">
        <div class="col-md-6 mx-auto">
            <p class="" style="margin-top: 10rem; font-weight: 600; font-size: 1.2rem;">خوش اومدی :)</p>
        </div>
    </div>
<?php 

endif;
include_once "includes/footer.php";
?>
