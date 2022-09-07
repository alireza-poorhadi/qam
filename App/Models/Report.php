<?php

namespace App\Models;

use App\Models\contracts\MysqlBaseModel;

class Report extends MysqlBaseModel
{
    protected $table = 'reports';
}