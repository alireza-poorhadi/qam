<?php

namespace App\Utils;

class Validator
{
    public static function isValidEmail($email)
    {
        return filter_var($email, FILTER_SANITIZE_EMAIL);
    }
}