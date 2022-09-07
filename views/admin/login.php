<?php 
require_once 'header.php';
isset($_SESSION['class']) ? $class = $_SESSION['class'] : $class = '';
unset($_SESSION['class']);
?>

<body>
<div id="container">
    <div class="mLoingBox">
        <div id="img">
            <img src="<?= siteURL('assets/img/NFLogo.png') ?>" alt="سامانه پرسش و پاسخ نسل فردا">
        </div>
        <div class="innerBox">
            <form action="<?= siteURL('admin/login') ?>" class="pure-form pure-form-stacked" method="POST">
                <div class="pageItemTitle formTitle">ورود مدیر به سامانه</div>
                <?php if(isset($_SESSION['loginMsg'])): ?>
                    <div class="message <?= $class ?>"><?= $_SESSION['loginMsg'] ?></div>
                    <?php unset($_SESSION['loginMsg']) ?>
                <?php endif; ?>
                <input type="text" name="username" placeholder="نام کاربری یا ایمیل شما">
                <input type="password" name="password" placeholder="رمز عبور">
                <br>
                <!-- <input type="checkbox" name=""> مرا به خاطر داشته باش -->
                <div class="center">
                    <button class="pure-button pure-button-primary">ورود</button>
                </div>
                <br>
            </form>
        </div>
    </div>
</div>

<?php require_once 'footer.php'; ?>