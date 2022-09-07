<?php

namespace App\Models;

use App\Models\contracts\MysqlBaseModel;

class Category extends MysqlBaseModel
{
    protected $table = 'categories';
}