<?php
use App\Classes\Auth;
?>
<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="assets/css/bootstrap.min.css" >
    <link rel="stylesheet" href="assets/css/style.css" >
    <title>صفحه اصلی</title>
  </head>
  <body>

  <nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="container">
      <a class="navbar-brand" href="index.php">صفحه اصلی</a>
      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
  
      <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav mr-auto">
          <?php if (!Auth::check()): ?>
            <li class="nav-item active">
              <a class="nav-link" href="login.php">ورود</a>
            </li>
            <li class="nav-item active">
              <a class="nav-link" href="register.php">ثبت نام</a>
            </li>
          <?php else: ?>
            <li class="nav-item active">
              <a class="nav-link" href="logout.php">خروج</a>
            </li>
            <li class="nav-item active">
              <a class="nav-link" href="userProfile.php">سلام <strong><?= Auth::user()->name ?></strong></a>
            </li>
          <?php endif; ?>
        </ul>
      </div>
    </div>
  </nav>