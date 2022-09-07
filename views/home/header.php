<?php
if (isset($_SESSION['admin'])) {
    $username = $_SESSION['admin']['username'];
    $fullname = $_SESSION['admin']['fullname'];
    $id = $_SESSION['admin']['id'];
}

if (isset($_SESSION['user'])) {
    $username = $_SESSION['user']['username'];
    $fullname = $_SESSION['user']['fullname'];
    $id = $_SESSION['user']['id'];
}
$defaultUserId = findDefaultUserId();

?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>سامانه پرسش و پاسخ</title>
    <script src="<?= siteURL('/assets/js/jquery.min.js') ?>"></script>
    <script src="<?= siteURL('/assets/js/myJS.js') ?>"></script>
    <script src="<?= siteURL('/assets/js/jquery-modal.js') ?>"></script>
    <link rel="stylesheet" href="<?= siteURL('/assets/css/pure2.css') ?>">
    <link rel="stylesheet" href="<?= siteURL('/assets/css/pure-responsive2.css') ?>">
    <link rel="stylesheet" href="<?= siteURL('/assets/css/style.css') ?>">
    <link rel="stylesheet" href="<?= siteURL('/assets/css/jquery.slModal.css') ?>">
</head>
<body>
<div id="container">
    <div id="topBar" class="pure-g">
        <div><img src="<?= siteUrl($logo) ?>" alt="<?= $settings['companyName'] ?>" width="50"></div>
        <div class="pure-u-1">
            <h1><?= $settings['companyName'] ?></h1>
        </div>
    </div>

    <div id="sideMenu">
        <div id="searchHandle">
            <div id="options">جستجوی سوالات</div>
            <div id="img"><img src="<?= siteURL('/assets/img/searchIcon.png') ?>" alt="گزینه های نمایش"></div>
        </div>
        <div id="frm">
            <form action="" class="pure-form" method="GET">
                <input type="text" name="s" placeholder="جستجو در سوالات">
                <br>
                <select name="selectCat">
                    <option value="all">همه دسته بندیها</option>
                    <?php foreach($categories as $category): ?>
                    <option value="<?= $category['id'] ?>"><?= $category['title'] ?></option>
                    <?php endforeach; ?>
                </select>
                <br>
                <select name="selectLevel">
                    <option value="all">همه سطوح</option>
                    <?php foreach($levels as $level): ?>
                    <option value="<?= $level['id'] ?>"><?= $level['title'] ?></option>
                    <?php endforeach; ?>
                </select>
                <br>
                پرسش از:
                <select name="selectTeacher">
                    <?php foreach($teachers as $teacher): ?>
                    <option value="<?= $teacher['id'] ?>"><?= $teacher['fullname'] ?></option>
                    <?php endforeach; ?>
                </select>
                
                <br><br>
                <button class="pure-button pure-button-primary">جستجو</button>

            </form>
        </div>
    </div>