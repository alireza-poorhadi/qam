<?php require_once 'header.php';
isset($_SESSION['class']) ? $class = $_SESSION['class'] : $class = '';
unset($_SESSION['class']);
?>
    <title>ورود به سامانه</title>
</head>
<body>
<div id="container">
    <div class="loginBox">
        <div id="img">
            <img src="<?= siteUrl($logo) ?>" alt="<?= $settings['companyName'] ?>" width="50">
        </div>
        <div class="innerBox">
            <form action="<?= siteURL('login') ?>" class="pure-form pure-form-stacked" method="POST">
                <div class="pageItemTitle formTitle">ورود به سامانه</div>
                <?php if (isset($_SESSION['loginMsg'])): ?>
                    <div class="message <?= $class ?>"><?= $_SESSION['loginMsg'] ?></div>
                    <?php unset($_SESSION['loginMsg']) ?>
                <?php endif; ?>
                <input type="text" name="username" placeholder="نام کاربری">
                <input type="password" name="password" placeholder="رمز عبور">
                <br>
                <!-- <input type="checkbox" name=""> مرا به خاطر داشته باش -->
                <div class="center">
                <button class="pure-button pure-button-primary">ورود</button>
                </div>
            </form>
            <p style="text-align: center;">عضو نیستید؟ <a href="<?= siteURL('regPage') ?>">ثبت نام کنید</a></p>
        </div>
    </div>
</div>
<script>
    document.getElementById('container').style.height = innerHeight + 'px';
</script>
</body>
</html>