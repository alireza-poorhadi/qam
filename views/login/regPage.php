<?php require_once 'header.php';
isset($_SESSION['class']) ? $class = $_SESSION['class'] : $class = '';
unset($_SESSION['class']);
?>
    <title>ثبت نام</title>
</head>
<body>
<div id="container">
    <div class="loginBox">
        <div id="img">
            <img src="<?= siteUrl($logo) ?>" alt="<?= $settings['companyName'] ?>" width="50">
        </div>
        <div class="innerBox">
            <form action="<?= siteURL('register') ?>" class="pure-form pure-form-stacked" method="POST">
                <div class="pageItemTitle formTitle">ثبت نام</div>
                <?php if (isset($_SESSION['regMsg'])): ?>
                    <div class="message <?= $class ?>"><?= $_SESSION['regMsg'] ?></div>
                    <?php unset($_SESSION['regMsg']) ?>
                <?php endif; ?>
                <input type="text" name="username" placeholder="نام کاربری ">
                <input type="text" name="fullname" placeholder="نام نمایشی">
                <input type="password" name="password" placeholder="رمز عبور (حداقل شش کاراکتر)">
                <input type="password" name="repassword" placeholder="تکرار رمز عبور">
                <br>
                <div class="center"><button class="pure-button pure-button-primary">ارسال</button></div>
            </form>
        </div>
    </div>
</div>
<script>
    document.getElementById('container').style.height = innerHeight + 'px';
</script>
</body>
</html>

