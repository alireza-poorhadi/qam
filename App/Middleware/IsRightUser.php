<?php

namespace App\Middleware;

use App\Middleware\contracts\MiddlewareInterface;

class IsRightUser implements MiddlewareInterface {
    public function handle()
    {
        global $request;
        if(isGuest()) {
            die('Access Forbidden');
        }
        if(isUser() or isTeacher()) {
            if($request->routeParam('user_id') != $_SESSION['user']['id']) {
                die('Access Forbidden');
            }
        }
        if(isAdmin()) {
            if($request->routeParam('user_id') != $_SESSION['admin']['id']) {
                die('Access Forbidden');
            }
        }
    }
}