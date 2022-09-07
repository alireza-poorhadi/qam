<?php require_once 'header.php'; ?>
<?php 
isset($_SESSION['whichDiv']) ? $whichDiv = $_SESSION['whichDiv'] : $whichDiv = 'rectContainer';
unset($_SESSION['whichDiv']);
isset($_SESSION['class']) ? $class = $_SESSION['class'] : $class = '';
unset($_SESSION['class']);
?>

<body>
<div id="container" class="container">

    <div class="pure-g">
        <div class="pure-u-1">
            <div class="center">
                <img src="<?= siteURL($logo) ?>" alt="<?= $settings['companyName'] ?>" width="50">
            </div>
        </div>
    </div>
    <div class="welcomeBox">
        مدیر محترم <span style="color:red;"><?=  $_SESSION['admin']['fullname']?></span> خوش آمدید.
        <a href="<?= siteURL() ?>" class="pure-button button-success">صفحه اصلی</a>
        <a href="<?= siteURL('users') ?>" class="pure-button button-success">مدیریت کاربران</a>
    <a href="?logout=1" class="pure-button button-warning">خروج</a>
    </div>

    <div class="pure-g">

        <div class="pure-u-1">
            <div class="mBox">
                <?php if (isset($_SESSION['msg'])): ?>
                    <div class="message <?= $class ?>"><?= $_SESSION['msg'] ?></div>
                    <?php unset($_SESSION['msg']) ?>
                <?php endif; ?>
                <div class="pageItemTitle"><img src="<?= siteURL('/assets/img/settingIcon.png') ?>"> تنظیمات</div>
                <form action="<?= siteURL('admin/setting') ?>" class="pure-form pure-form-stacked" method="POST" enctype="multipart/form-data">
                    <input type="text" name="companyName" placeholder="نام شرکت یا موسسه شما">
                    <span>نام فعلی: <?= $settings['companyName'] ?></span>
                    <br><br>
                    <input type="text" name="pageSize" placeholder="تعداد نمایش سوال در هر صفحه">
                    <span>مقدار فعلی: <?= $settings['pageSize'] ?> عدد</span>
                    <br><br>
                    آپلود لوگوی شما: <input type="file" name="uploadedLogo">
                    <br><br>
                    <div class="center">
                        <button class="pure-button button-success">اجرای تنظیمات</button>
                    </div>
                </form>
            </div>
        </div>
        <div class="pure-u-1">
            <div class="mBox" id="admin">
                <div class="pageItemTitle"><img src="<?= siteURL('/assets/img/addAdminIcon.png') ?>"> افزودن مدیر</div>
                <?php if(isset($_SESSION['adminMsg'])): ?>
                    <div class="message <?= $class ?>"><?= $_SESSION['adminMsg'] ?></div>
                    <?php unset($_SESSION['adminMsg']) ?>
                <?php endif; ?>
                
                <form action="<?= siteURL('admin/addAdmin') ?>" class="pure-form pure-form-stacked" method="POST">
                    <input type="text" name="username" placeholder="نام کاربری (به لاتین وارد شود)">
                    <br>
                    <div class="center">
                        <button class="pure-button button-success">ارسال</button>
                    </div>
                </form>

                <div class="pageItemTitle">مدیران</div>
                <!-- ************************** -->
                <div class="adminBox" id="2">
                    <div class="admin">
                        <div id="adminName">
                            <table class="pure-table" style="width: 100%;">
                                <?php if (count($admins) == 0): ?>
                                    <p>مدیری وجود ندارد.</p>
                                <?php endif; ?>
                                <tr>
                                    <td><b>نام کاربری</b></td>
                                    <td><b>نام نمایشی</b></td>
                                    <td><b>عملیات</b></td>
                                </tr>
                                <?php foreach ($admins as $admin): ?>
                                    <tr>
                                        <td><?= $admin['username'] ?></td>
                                        <td><?= $admin['fullname'] ?></td>
                                        <td><a href="<?= siteURL() . 'admin/delete/'.$admin['id'] ?>" class="delete">حذف</a></td>
                                    </tr>
                                <?php endforeach; ?>
                            </table>
                        </div>
        
                    </div>
                </div>
                <!-- ************************** -->
                <br>
            </div>
        </div>

        <div class="pure-u-1">
            <div class="mBox" id="teacher">
                <div class="pageItemTitle"><img src="<?= siteURL('/assets/img/addAdminIcon.png') ?>"> افزودن پاسخ‌دهنده</div>
                <?php if(isset($_SESSION['teacherMsg'])): ?>
                    <div class="message <?= $class ?>"><?= $_SESSION['teacherMsg'] ?></div>
                    <?php unset($_SESSION['teacherMsg']) ?>
                <?php endif; ?>
                
                <form action="<?= siteURL('admin/addTeacher') ?>" class="pure-form pure-form-stacked" method="POST">
                    <input type="text" name="username" placeholder="نام کاربری (به لاتین وارد شود)">
                    <input type="text" name="fullname" placeholder="نام نمایشی">
                    <input type="password" name="password" placeholder="رمز عبور">
                    <input type="password" name="repassword" placeholder="تکرار رمز عبور">
                    <br>
                    <div class="center">
                        <button class="pure-button button-success">ارسال</button>
                    </div>
                </form>

                <div class="pageItemTitle">پاسخ‌دهندگان</div>
                <!-- ************************** -->
                <div class="adminBox">
                    <div class="admin">
                        <div id="adminName">
                            <table class="pure-table pure-table-horizontal" style="width: 100%;">
                                <?php if (count($teachers) == 0): ?>
                                    <p>پاسخ‌دهنده‌ای وجود ندارد.</p>
                                <?php endif; ?>

                                <tr>
                                    <td><b>نام کاربری</b></td>
                                    <td><b>نام نمایشی</b></td>
                                    <td><b>عملیات</b></td>
                                </tr>
                                    
                                <?php foreach ($teachers as $teacher): ?>
                                <tr>
                                    <td><?= $teacher['username'] ?></td>
                                    <td><?= $teacher['fullname'] ?></td>
                                    <td><a href="<?= siteURL() . 'admin/deleteTeacher/'.$teacher['id'] ?>" class="delete">حذف</a></td>
                                </tr>
                                <?php endforeach; ?>
                            </table>
                        </div>
        
                    </div>
                </div>
                <!-- ************************** -->
                <br>
            </div>
        </div>

        
    </div>

    <div class="pure-g">
        <div class="pure-u-1">
            <div class="mBox" id="category">
                <div class="pageItemTitle"><img src="<?= siteURL('/assets/img/addCat.png') ?>"> افزودن دسته بندی جدید</div>
                <?php if(isset($_SESSION['categoryMsg'])): ?>
                    <div class="message <?= $class ?>"><?= $_SESSION['categoryMsg'] ?></div>
                    <?php unset($_SESSION['categoryMsg']) ?>
                <?php endif; ?>
                
                <form action="<?= siteURL('admin/addCategory') ?>" class="pure-form" method="POST">
                    <input type="text" name="title" placeholder="نام دسته بندی جدید">
                    <div class="center"><button class="pure-button button-success">افزودن</button></div>
                </form>
                <br>

                <div class="pageItemTitle">تمام دسته‌بندی‌ها</div>
                <table class="pure-table pure-table-horizontal" style="width: 100%;">
                    
                    <tbody>
                        <?php if (count($categories) == 0): ?>
                            <tr>
                                <td>دسته بندی وجود ندارد!</td>
                            </tr>
                        <?php endif; ?>
                        <?php foreach ($categories as $category): ?>
                        <tr>
                            <td><?= $category['title'] ?></td>
                            <td><a href="<?= siteURL('admin/editCategory/') . $category['id'] ?>" class="edit">ویرایش</a></td>
                            <td><a href="<?= siteUrl('admin/deleteCategory/' . $category['id']) ?>" class="delete">حذف</a></td>
                        </tr>
                        <?php endforeach; ?>

                    </tbody>
                </table>
                <br>
                
            </div>
                
            </div>

            
        </div>

        <div class="pure-u-1">
            <div class="mBox" id="level">
                <div class="pageItemTitle"><img src="<?= siteURL('/assets/img/levels.png') ?>"> افزودن سطح جدید</div>
                <?php if(isset($_SESSION['levelMsg'])): ?>
                    <div class="message <?= $class ?>"><?= $_SESSION['levelMsg'] ?></div>
                    <?php unset($_SESSION['levelMsg']) ?>
                <?php endif; ?>
                
                <form action="<?= siteURL('admin/addLevel') ?>" class="pure-form" method="POST">
                    <input type="text" name="title" placeholder="نام  سطح جدید">
                    <div class="center"><button class="pure-button button-success">افزودن</button></div>
                </form>
                <br>

                <div class="pageItemTitle">تمام سطح‌ها</div>
                
                <table class="pure-table pure-table-horizontal" style="width: 100%;">
                        <?php if (count($levels) == 0): ?>
                            <tr>
                                <td>سطحی وجود ندارد!</td>
                            </tr>
                        <?php endif; ?>
                    <tbody>
                        <?php foreach ($levels as $level): ?>
                        <tr>
                            <td><?= $level['title'] ?></td>
                            <td><a href="<?= siteURL('admin/editLevel/') . $level['id'] ?>" class="edit">ویرایش</a></td>
                            <td><a href="<?= siteUrl('admin/deleteLevel/' . $level['id']) ?>" class="delete">حذف</a></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                <br>
                
            </div>
                
            </div>

            
        </div>

        

</div>
<script>
    var container = document.getElementById('container');
    var level = document.getElementById('level');
    var category = document.getElementById('category');
    var admin = document.getElementById('admin');
    var teacher = document.getElementById('teacher');
    var rectContainer = container.getBoundingClientRect();
    var rectLevel = level.getBoundingClientRect();
    var rectCategory = category.getBoundingClientRect();
    var rectAdmin = admin.getBoundingClientRect();
    var rectTeacher = teacher.getBoundingClientRect();
    window.scroll({
        top: Math.round(<?=$whichDiv?>.top),
        behavior: 'instant'
    });
</script>

<?php require_once 'footer.php'; ?>


`