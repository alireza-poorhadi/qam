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
    <script src="<?= siteURL('/assets/js/jquery.min.js') ?>"></script>
    <link rel="stylesheet" href="<?= siteURL('/assets/css/pure2.css') ?>">
    <link rel="stylesheet" href="<?= siteURL('/assets/css/pure-responsive2.css') ?>">
    <link rel="stylesheet" href="<?= siteURL('/assets/css/style.css') ?>">
    <title>طرح سوال</title>
</head>
<body>
<div id="container">
    <div class="mkquBox">
        <div id="img">
            <img src="<?= siteUrl($logo) ?>" alt="<?= $settings['companyName'] ?>" width="50">
        </div>
        <?php if(isGuest()): ?>
            <div class="errorMsg">
                <p>فقط دوستانی که عضو سایت هستند می توانند طرح سوال کنند.</p>
            </div>
            <div class="center">
                <a href="<?= siteURL('loginpage') ?>" class="pure-button button-success">ورود</a>
            </div>
        <?php else: ?>
        <div class="innerBox">
            <form action="<?= siteURL('saveQu') ?>" class="pure-form pure-form-stacked" method="POST">
                <div class="pageItemTitle formTitle">طرح سوال</div>
                <?php if(isset($_SESSION['mkquMsg'])): ?>
                    <div class="message <?= $class ?>"><?= $_SESSION['mkquMsg'] ?></div>
                    <?php unset($_SESSION['mkquMsg']) ?>
                <?php endif; ?>
                <input type="text" name="title" placeholder="عنوان سوال">
                <select name="category" id="" class="fullWidth">
                    <option value="">دسته‌بندی را انتخاب کنید</option>
                    <?php foreach($categories as $category): ?>
                    <option value="<?= $category['id'] ?>"><?= $category['title'] ?></option>
                    <?php endforeach; ?>
                    
                </select>
                <select name="level" id="" class="fullWidth">
                    <option value="">سطح را انتخاب کنید</option>
                    <?php foreach($levels as $level): ?>
                    <option value="<?= $level['id'] ?>"><?= $level['title'] ?></option>
                    <?php endforeach; ?>
                </select>
                <select name="teacherId" id="" class="fullWidth">
                    <option value="">سوال از:</option>
                    <?php foreach($teachers as $teacher): ?>
                    <option value="<?= $teacher['id'] ?>"><?= $teacher['fullname'] ?></option>
                    <?php endforeach; ?>
                </select>
                <textarea name="content" id="" cols="30" rows="10" placeholder="سوال شما"></textarea>
                <div class="center"><button class="pure-button pure-button-primary">ارسال سوال</button></div>
            </form>
            <br>
            <div class="center"><a href="<?= siteUrl() ?>" class="pure-button">بازگشت به صفحه اصلی</a></div>
        </div>
        <?php endif; ?>
    </div>
</div>
<script>
    $('#container').css('minHeight', innerHeight + 'px');
</script>
</body>
</html>