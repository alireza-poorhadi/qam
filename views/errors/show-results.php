<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>نتایج</title>
</head>

<body>
    <div class="container">
        <?= isset($addAdminMsg) ? $addAdminMsg : ''  ?>
        <?= isset($addCategoryMsg) ? $addCategoryMsg : '' ?>
        <?= isset($addLevelMsg) ? $addLevelMsg : '' ?>
        <?= isset($loginAdminMsg) ? $loginAdminMsg : '' ?>
    </div>
    <script>
        setTimeout(function(){
            location.href = "<?= siteURL('admin'); ?>";
        }, 1000);
    </script>
</body>

</html>