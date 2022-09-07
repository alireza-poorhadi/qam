<?php

namespace App\Models;

use App\Models\contracts\MysqlBaseModel;

class Question extends MysqlBaseModel
{
    protected $table = 'questions';
}