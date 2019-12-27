<?php

namespace App\Models;

use App\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Learn extends Model
{
    use SoftDeletes;

    const STATUS_NORMAL = 0;
    const STATUS_SHOW = 1;

    protected $table = 'learns';
    protected $primaryKey = 'id';

    public function hasOneUsers()
    {
        return $this->hasOne(User::class, 'id', 'admin_id');
    }

}
