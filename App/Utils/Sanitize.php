<?php

namespace App\Utils;

class Sanitize
{
    public static function notGiven($data)
    {
        if (is_null($data))
            return true;

        if (empty($data))
            return true;

        return false;
    }

    public static function clean($data)
    {
        $data = htmlspecialchars($data);
        $data = stripslashes($data);
        $data = trim($data);
        $data = filter_var($data, FILTER_SANITIZE_ADD_SLASHES);
        return $data;
    }
}