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
    <script src="<?= siteURL('/assets/js/myJS.js') ?>"></script>
    <link rel="stylesheet" href="<?= siteURL('/assets/css/pure2.css') ?>">
    <link rel="stylesheet" href="<?= siteURL('/assets/css/pure-responsive2.css') ?>">
    <link rel="stylesheet" href="<?= siteURL('/assets/css/style.css') ?>">
    <title>کاربران</title>
</head>
<body>
<div id="container" class="container">

    <div class="pure-g">
        <div class="pure-u-1">
            <div class="center">
                <img src="<?= siteURL($logo) ?>" alt="<?= $settings['companyName'] ?>" width="50">
            </div>
        </div>
    </div>

    <div class="pure-g">

        <div class="pure-u-1">
            <div class="mBox">
                <div class="center"><a href="<?= siteUrl() ?>" class="pure-button">بازگشت</a></div>
                <div class="pageItemTitle"><img src="<?= siteURL('/assets/img/users.png') ?>"> کاربران <?= $allCount ?></div>
                <?php if (isset($_SESSION['delMsg'])): ?>
                <div class="message <?= $class ?>"><?= $_SESSION['delMsg'] ?></div>
                <?php unset($_SESSION['delMsg']) ?>
                <?php endif; ?>
                <br>
                <table class="pure-table pure-table-horizontal" style="width: 100%;">
                    <thead>
                        <tr style="text-align: right;">
                            <td>نام</td>
                            <td>نقش</td>
                            <td>پرسشها</td>
                            <td>پاسخها</td>
                            <?php if(isAdmin()): ?>
                            <td>حذف</td>
                            <?php endif; ?>
                        </tr>
                    </thead>
                    <?php foreach($users as $user): ?>
                    <tr>
                        <td><?= $user['fullname'] ?></td>
                        <td>
                            <?php 
                               if ($user['role'] == 'admin') {
                                   echo 'مدیر';
                               } else if ($user['role'] == 'teacher') {
                                   echo 'پاسخ دهنده';
                               } else if ($user['role'] == 'user') {
                                   echo 'کاربر';
                               }
                            ?>
                        </td>
                        <td><a href="<?= siteURL('showUserQ/') . $user['id'] ?>"><?= getCountQuestions($user['id']) ?></a></td>
                        <td><a href="<?= siteURL('showUserA/') . $user['id'] ?>"><?= getCountAnswers($user['id']) ?></a></td>
                        <?php if(isAdmin()): ?>
                        <td><a href="<?= siteURL('removeUser/') . $user['id'] ?>" class="delete">حذف</a></td>
                        <?php endif; ?>
                    </tr>
                    <?php endforeach; ?>
                </table>
                <br>
                <div class="center"><a href="<?= siteUrl() ?>" class="pure-button">بازگشت</a></div>
            </div>
            <div class="pagination clearfix">
            <?php if ($pageCount > 1): ?>
                <?php for ($i = 1; $i <= $pageCount; $i++) : ?>
                    <a href="<?= queryBuilder('page=' . $i); ?>" class="<?= ($page == $i) ? 'active' : '' ?>">
                        <li><?= $i; ?></li>
                    </a>
                <?php endfor; ?>
            <?php endif; ?>
        </div>
        </div>
        
    </div>


</div>
</body>
</html>