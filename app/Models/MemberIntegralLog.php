<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MemberIntegralLog extends Model
{
    use SoftDeletes;

    protected $table = 'members_integral_log';

    protected $primaryKey = 'id';
}
