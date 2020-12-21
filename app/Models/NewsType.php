<?php

namespace App\Models;

use App\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class NewsType extends Model
{
    use SoftDeletes;

    const STATUS_NORMAL = 0;
    const STATUS_SHOW = 1;

    protected $table = 'news_type';
    protected $primaryKey = 'id';

}
