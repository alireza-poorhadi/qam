<?php

# Front Controller
error_reporting(0);
require_once 'bootstrap/init.php';

use Hekmatinasser\Verta\Verta;
use App\Core\Routing\Router;
$router = new Router();
$router->run();