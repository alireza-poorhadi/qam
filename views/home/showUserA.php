<!doctype html>
<html lang="fa">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>پاسخهای کاربر</title>
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
        <div class="center"><img src="<?= siteUrl($logo) ?>" alt="<?= $settings['companyName'] ?>" width="50"></div>
    </div>
    
    <div id="body" class="pure-g">
        <div class="pure-u-1">
        <div class="center"><a href="<?= siteUrl('users') ?>" class="pure-button">بازگشت</a></div>
            <div class="questionBox">
                <?php if(sizeof($answers) == 0): ?>
                    <p style="color: white; text-align: center;">پاسخی وجود ندارد.</p>
                <?php endif; ?>
                <?php foreach($answers as $answer): ?>
                <div class="ans">
                    <span class="aMeta">پاسخ از: <?= find($answer['teacher_id'], 'Teacher')->fullname ?> </span>
                    <span class="aMeta"><?= $verta->createTimestamp(strtotime($answer['created_at']))->format('H:j - d / m / Y') ?></span>
                    <br>
                    <p><?= $answer['content'] ?></p]>
                    <p class="answerTo">در پاسخ به:</p>
                    <div class="a">
                        <?php $question = findQuestion($answer['question_id']) ?>
                            <span><b><?= $question['title'] ?></b></span><br>
                            <span class="qMeta">پرسش از: <?= find($question['teacher_id'], 'Teacher')->fullname ?></span>
                            <span class="qMeta">پرسش کننده: <?= find($question['user_id'], 'Teacher')->fullname ?></span>
                            <span class="qMeta">دسته‌بندی: <?= find($question['category_id'], 'Category')->title ?></span>
                            <span class="qMeta">سطح: <?= find($question['level_id'], 'Level')->title ?></span>
                            <span class="qMeta"><?= $verta->createTimestamp(strtotime($question['created_at']))->format('H:j - d / m / Y') ?></span>
                    </div>
                    <div class="qContent"><span><?= $question['content'] ?></span></div>
                            
                        

                </div>
                <?php endforeach; ?>
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
            <div class="center"><a href="<?= siteUrl('users') ?>" class="pure-button">بازگشت</a></div>
            <div id="goUp">
                <img src="<?= siteURL('/assets/img/goUpIcon.png') ?>" title="ابتدای صفحه">
            </div>
        </div>
    </div>
    
<?php require_once 'footer.php';