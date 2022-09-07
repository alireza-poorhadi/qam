<?php

namespace App\Utils;

class Url
{
    public static function currentWhole()
    {
        return (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
    }

    public static function currentRoute()
    {
        return strtok($_SERVER['REQUEST_URI'], '?');
    }
}
