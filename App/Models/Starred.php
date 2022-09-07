<?php

namespace App\Models;

use App\Models\contracts\MysqlBaseModel;

class Starred extends MysqlBaseModel
{
    protected $table = 'starred';
}