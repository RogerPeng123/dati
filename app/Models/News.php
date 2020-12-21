<?php

namespace App\Models;

use App\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class News extends Model
{
    //

    use SoftDeletes;

    protected $table = 'news';
    protected $primaryKey = 'id';

    const STATUS_NORMAL = 0;
    const STATUS_SHOW = 1;

    public function hasOneUsers()
    {
        return $this->hasOne(User::class, 'id', 'admin_id');
    }
}
