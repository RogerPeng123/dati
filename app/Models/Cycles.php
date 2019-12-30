<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Cycles extends Model
{
    use SoftDeletes;

    const NORMAL_STATUS = 0;
    const SHOW_STATUS = 1;

    const TYPE_SPECIAL_NORMAL = 1;
    const TYPE_SPECIAL_TOP = 2;

    protected $table = 'question_cycle';

    protected $primaryKey = 'id';
}
