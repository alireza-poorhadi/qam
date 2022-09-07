<?php

namespace App\Middleware;

use App\Middleware\contracts\MiddlewareInterface;

class IsAdmin implements MiddlewareInterface {
    public function handle()
    {
        if (!isset($_SESSION['admin'])) {
            redirect('admin/loginPage');
            exit;
        }
    }
}