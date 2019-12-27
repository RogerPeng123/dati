<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LearnReadLog extends Model
{
    use SoftDeletes;

    protected $table = 'learn_read_log';
    protected $primaryKey = 'id';

}
