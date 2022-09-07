<?php
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

define('BASEPATH', realpath(__DIR__ . '/../'));

require_once BASEPATH . '/vendor/autoload.php';
require_once BASEPATH . '/bootstrap/config.php';
require_once  BASEPATH . '/helpers/helpers.php';

$dotenv = Dotenv\Dotenv::createImmutable(BASEPATH);
$dotenv->load();

$request = new \App\Core\Request();

date_default_timezone_set('Asia/Tehran');

use Hekmatinasser\Verta\Verta;

$verta = new Verta();

require_once  BASEPATH . '/routes/web.php';

