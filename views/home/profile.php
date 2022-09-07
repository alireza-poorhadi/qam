<?php
isset($_SESSION['class']) ? $class = $_SESSION['class'] : $class = '';
unset($_SESSION['class']);
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="<?= siteURL('/assets/css/pure2.css') ?>">
    <link rel="stylesheet" href="<?= siteURL('/assets/css/pure-responsive2.css') ?>">
    <link rel="stylesheet" href="<?= siteURL('/assets/css/style.css') ?>">
    <title>پروفایل</title>
</head>
<body>
<div id="container">
    <div class="loginBox">
        <div id="img">
            <img src="<?= siteUrl($logo) ?>" alt="<?= $settings['companyName'] ?>" width="50">
        </div>
        <div class="innerBox">
            <form action="<?= siteURL('saveProfile') ?>" class="pure-form pure-form-stacked" method="POST">
                <div class="pageItemTitle formTitle">ویرایش پروفایل</div>
                <?php if (isset($_SESSION['profileMsg'])): ?>
                    <div class="message <?= $class ?>"><?= $_SESSION['profileMsg'] ?></div>
                    <?php unset($_SESSION['profileMsg']) ?>
                <?php endif; ?>
                <input type="hidden" name="userId" value="<?= $user['id'] ?>"> 
                <input type="text" name="username" placeholder="نام کاربری " value="<?= $user['username'] ?>">
                <input type="text" name="fullname" placeholder="نام نمایشی" value="<?= $user['fullname'] ?>">
                <input type="password" name="password" placeholder="رمز عبور جدید(حداقل شش کاراکتر)">
                <input type="password" name="repassword" placeholder="تکرار رمز عبور">
                <br>
                <div class="center"><button class="pure-button pure-button-primary">ذخیره</button></div>
            </form>
            <br>
            <div class="center"><a href="<?= siteUrl() ?>" class="pure-button">بازگشت</a></div>
        </div>
    </div>
</div>
<script>
    document.getElementById('container').style.height = innerHeight + 'px';
</script>
</body>
</html>

