<?php
$settingFile = BASEPATH . '/storage/json/setting.json';
$h = fopen($settingFile, 'r');
$setting = fread($h, filesize($settingFile));
$setting = json_decode($setting);
$companyName = $setting->companyName;
$pageSize = $setting->pageSize;
$settings = [
    'companyName' => $companyName,
    'pageSize' => $pageSize,
];
$logo = Glob(BASEPATH. '/assets/img/NFLogo.*');
$logo = strstr($logo[0], '/assets');
