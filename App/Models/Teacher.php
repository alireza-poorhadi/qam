<?php

namespace App\Models;

use App\Models\contracts\MysqlBaseModel;

class Teacher extends MysqlBaseModel
{
    protected $table = 'teachers';
}