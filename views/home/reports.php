<?php 
$defaultUserId = findDefaultUserId();
if (isset($_SESSION['admin'])) {
    $username = $_SESSION['admin']['username'];
    $fullname = $_SESSION['admin']['fullname'];
    $id = $_SESSION['admin']['id'];
}
?>

<!doctype html>
<html lang="fa">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>موارد گزارش شده</title>
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

    <div id="choices">
        
    </div>
    
    <div id="body" class="pure-g">
        <div class="pure-u-1">
            <div id="questions">
                <div class="center"><a href="<?= siteUrl() ?>" class="pure-button">بازگشت</a></div>
                <div class="pageItemTitle">تعداد موارد گزارش شده: <?= $count ?></div>

                <?php if (isset($_SESSION['answerMsg'])): ?>
                    <div class="message <?= $class ?>"><?= $_SESSION['answerMsg'] ?></div>
                    <?php unset($_SESSION['answerMsg']) ?>
                <?php endif; ?>

                
                <?php if(sizeof($questions) == 0): ?>
                    <p style="color: white;">مورد گزارش شده‌ای وجود ندارد.</p>
                <?php endif; ?>
                <?php foreach ($questions as $question): ?>
                    <div class="questionBox <?= (isAdmin() and $question['verified'] == 0) ? 'questionBoxPending' : '' ?>">
                        <div class="question">
                                <div class="a">
                                    <span><b><?= $question['title'] ?></b></span><br>
                                    <span class="qMeta">پرسش از: <?= find($question['teacher_id'], 'Teacher')->fullname ?></span>
                                    <span class="qMeta">پرسش کننده: <?= find($question['user_id'], 'Teacher')->fullname ?></span>
                                    <span class="qMeta">دسته‌بندی: <?= find($question['category_id'], 'Category')->title ?></span>
                                    <span class="qMeta">سطح: <?= find($question['level_id'], 'Level')->title ?></span>
                                    <span class="qMeta"><?= $verta->createTimestamp(strtotime($question['created_at']))->format('H:j - d / m / Y') ?></span>
                                    <span class="qMeta">گزارش شده توسط: <?= $question['reporting_user'] ?></span>
                                    <span class="report qMeta" data-report="<?= $question['id'] ?>" data-reportingUserId="<?= $question['reporting_userId'] ?>" style="color: rgb(202, 60, 60);">حذف گزارش</span>
                                </div>
                                <div class="qContent"><span><?= $question['content'] ?></span></div>
                                
                                <?php $answers = findAnswers($question['id']) ?>
                                <?php foreach ($answers as $answer): ?>
                                <div class="answer">
                                    <span class="aMeta">پاسخ از: <?= find($answer['teacher_id'], 'Teacher')->fullname ?> </span>
                                    <span class="aMeta"><?= $verta->createTimestamp(strtotime($answer['created_at']))->format('H:j - d / m / Y') ?></span>
                                    <a href="<?= siteURL('removeAnswer/') . $answer['id'] ?>" style="<?= isAdmin() ? '' : 'display:none;' ?>"><span class="aMeta" style="color: red;">حذف</span></a>
                                        <br>
                                        
                                        <p><?= $answer['content'] ?></p]>
                                        
                                    </div>
                                    <?php endforeach; ?>
                        </div>
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
            <div id="goUp">
                <img src="<?= siteURL('/assets/img/goUpIcon.png') ?>" title="ابتدای صفحه">
            </div>
        </div>
    </div>
    <script>
        $(document).ready(function(){
            $('.a span.report').click(function(){
            var obj = $(this);
            var questionId = obj.attr('data-report');
            var userId = obj.attr('data-reportingUserId');
            $.ajax({
                    url: "<?= siteURL('report') ?>",
                    type: 'get',
                    data: {
                        questionId: questionId,
                        userId : userId
                    },
                    success: function(response) {
                        obj.parent().parent().slideUp();
                    }
            });
        });
        });
    </script>
<?php require_once 'footer.php';