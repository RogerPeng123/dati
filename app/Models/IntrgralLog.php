<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class IntrgralLog extends Model
{
    use SoftDeletes;

    const TYPE_LOGIN = 1;
    const TYPE_READ = 2;
    const TYPE_QUESTION_BANK = 3;
    const TYPE_LEAEN = 4;
    const TYPE_COLLECTION = 5;

    protected $table = 'intrgral_log';
    protected $primaryKey = 'id';


}
