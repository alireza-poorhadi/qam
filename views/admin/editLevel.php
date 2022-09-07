<?php require_once 'header.php'; ?>
<body>
    <div id="container" class="container">

        <div class="pure-g">
            <div class="pure-u-1">
                <div class="mBox">
                    <div class="pageItemTitle">ویرایش  سطح</div>
                    <form action="<?= siteURL('admin/saveLevel') ?>" class="pure-form" method="POST">
                        <input type="hidden" name="levelId" value="<?= $id ?>">
                        <input type="text" name="title" placeholder="نام  سطح جدید" value="<?= $title ?>">
                        <div class="center"><button class="pure-button button-success">ویرایش</button></div>
                    </form>
                </div>
            </div>
        </div>
    </div>

<?php require_once 'footer.php'; ?>