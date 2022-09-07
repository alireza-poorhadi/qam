<?php
isset($_SESSION['class']) ? $class = $_SESSION['class'] : $class = '';
unset($_SESSION['class']);
?>
<div id="choices">
        <ul>
            <a href="<?= siteURL('mkqu') ?>"><li><img src="<?= siteURL('/assets/img/qIcon.png') ?>"><span>طرح سوال</span></li></a>
            <?php if (!isUser() and !isAdmin() and !isTeacher()): ?>
            <a href="<?= siteURL('loginPage') ?>"><li><img src="<?= siteURL('/assets/img/studentIcon.png') ?>"><span> ورود</span></li></a>
            <?php endif; ?>
            <?php if(!isGuest()): ?>
            <a href="<?= siteURL('profile/') . $id ?>"><li><img src="<?= siteURL('/assets/img/studentIcon.png') ?>"><span>پروفایل</span></li></a>
            <a href="<?= siteURL('showStarredQ/') . $id ?>"><li><img src="<?= siteURL('/assets/img/bookmark.png') ?>"><span>پرسشهای نشان‌شده </span></li></a>
            <?php endif; ?>
            <?php if(isAdmin()): ?>
            <a href="<?= siteURL('showReports') ?>"><li><img src="<?= siteURL('/assets/img/report.png') ?>"><span> موارد گزارش شده (<?= reportedQuestionsCount() ?>)</span></li></a>
            <?php endif; ?>
            <a href="<?= siteURL('users') ?>"><li><img src="<?= siteURL('/assets/img/users.png') ?>"><span>کاربران</span></li></a>
            <?php if (isUser() or isAdmin() or isTeacher()): ?>
            <a href="?logout=1"><li><img src="<?= siteURL('/assets/img/logout.png') ?>"><span>خروج</span></li></a>
            <?php endif; ?>
        </ul>
</div>
<div class="welcomeBox">
        <?= isAdmin() ? 'مدیر محترم' : 'کاربر عزیز' ?>  <span style="color:red;">
    <?php
        if (isAdmin()) {
            echo $fullname;
        } elseif (isset($_SESSION['user'])) {
            echo $_SESSION['user']['fullname'];
        } else {
            echo 'میهمان';
        }
    ?>
    </span> خوش آمدید.
    
    </div>
<div id="body" class="pure-g">
    <div class="pure-u-1">
        <div id="questions">
            <div class="pageItemTitle"><?= isset($_GET['s']) ? 'نتایج جستجو برای '.'<b style="color:red;">'.$_GET['s'] .'</b>'. '<br>' : '' ?>تعداد سوالات: <?= $count ?></div>
            <?php if(isset($_GET['s'])): ?>
            <div class="center"><a href="<?= siteURL() ?>" style="color: white;">بازگشت به همه سوالات</a></div>
            <?php endif; ?>
            <br>
            <?php if (isset($_SESSION['answerMsg'])): ?>
                <div class="message <?= $class ?>"><?= $_SESSION['answerMsg'] ?></div>
                <?php unset($_SESSION['answerMsg']) ?>
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
                            </div>
                            <div class="qContent"><span><?= $question['content'] ?></span></div>
                            <?php if (isAdmin() or isUser() or isTeacher()): ?>
                                <div class="qManage br5">
                                    <a class="pure-button pure-button-primary dots">...</a>
                                    <ul class="qMenu" style="display: none;">
                                        <?php if ((find($question['teacher_id'], 'Teacher')->id) == $defaultUserId or (isTeacher() and $id == (find($question['teacher_id'], 'Teacher')->id))): ?>
                                            <li><button class="pure-button button-secondary qmr">افزودن پاسخ</button></li>
                                            <?php endif ?>
                                            <li class="star" data-star="<?= $question['id'] ?>"><button class="pure-button button-success"><?= canFindStarredQuestion($id, $question['id']) ? 'حذف نشان' : 'نشان کردن'?></button></li>
                                            <li class="report" data-report="<?= $question['id'] ?>"><button class="pure-button button-error"><?= canFindReportedQuestion($id, $question['id']) ? 'لغو گزارش' : 'گزارش پاسخ' ?></button></li>
                                            <?php if (isAdmin()): ?>
                                            <li id="<?= $question['id'] ?>" class="remove"><span class="pure-button button-warning">حذف</span></li>
                                            <li id="<?= $question['id'] ?>" class="verify" data-verified="<?= $question['verified'] ?>"><span class="pure-button button-<?= ($question['verified'] == 1) ? 'error' : 'success' ?>"><?= ($question['verified'] == 1) ? 'لغو تایید' : 'تایید' ?></span></li>
                                        <?php endif; ?>
                                    </ul>
                                </div>
                            <?php endif; ?>
                            <div class="answerToQ">
                                <form action="<?= siteURL('answer') ?>" method="POST">
                                    <input name="question_id" type="hidden" value="<?= $question['id'] ?>">
                                    <input name="pageNumber" type="hidden" value="<?= isset($_GET['page']) ? $_GET['page'] : 1 ?>">
                                    <input name="user_id" type="hidden" value="<?= $id ?>">
                                    <textarea name="answer" placeholder="پاسخ شما ..."></textarea>
                                    <div class="center"><button class="pure-button button-success">ارسال</button></div>
                                </form>
                            </div>
                            
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
        
        $('.qManage ul li.verify').click(function(){
            var obj = $(this);
            var id = obj.attr('id');
            var verified = obj.attr('data-verified');
            if (verified == 0) {
                var url = "<?= siteURL('verify') ?>";
                var className = 'pure-button button-error';
                var dataVerified = 1;
                var text = 'لغو تایید';
                var parentClass = 'questionBox';
            } else if (verified == 1) {
                var url = "<?= siteURL('unverify') ?>";
                var className = 'pure-button button-success';
                var dataVerified = 0;
                var text = 'تایید';
                var parentClass = 'questionBox questionBoxPending';
            }
            $.ajax({
                    url: url,
                    type: 'get',
                    data: {
                        questionId: id
                    },
                    success: function(response) {
                        obj.attr('class', className);
                        obj.attr('id', response);
                        obj.attr('data-verified', dataVerified);
                        obj.text(text);
                        obj.parent().parent().parent().parent().attr('class', parentClass);
                    }
            });
        });
        $('.qManage ul li.remove').click(function(){
            obj = $(this);
            var id = obj.attr('id');
            $.ajax({
                    url: "<?= siteURL('remove') ?>",
                    type: 'get',
                    data: {
                        questionId: id
                    },
                    success: function(response) {
                        if (response) {
                            obj.parent().parent().parent().parent().slideUp();
                        }
                    }
            });
        });

        $('.qManage ul li.star').click(function(){
            var obj = $(this);
            var questionId = obj.attr('data-star');
            var userId = <?= $id ?>;
            $.ajax({
                    url: "<?= siteURL('starred') ?>",
                    type: 'get',
                    data: {
                        questionId: questionId,
                        userId : userId
                    },
                    success: function(response) {
                        if(obj.find('button').text() == 'نشان کردن') {
                            obj.find('button').text('حذف نشان');
                        } else {
                            obj.find('button').text('نشان کردن');
                        }
                    }
            });
        });

        $('.qManage ul li.report').click(function(){
            var obj = $(this);
            var questionId = obj.attr('data-report');
            var userId = <?= $id ?>;
            $.ajax({
                    url: "<?= siteURL('report') ?>",
                    type: 'get',
                    data: {
                        questionId: questionId,
                        userId : userId
                    },
                    success: function(response) {
                        if(obj.find('button').text() == 'گزارش پاسخ') {
                            obj.find('button').text('لفو گزارش');
                        } else {
                            obj.find('button').text('گزارش پاسخ');
                        }
                    }
            });
        });

    });
</script>