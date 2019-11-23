<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Question extends Model
{
    use SoftDeletes;

    protected $table = 'question';

    protected $primaryKey = 'id';

    public function questionOptions()
    {
        return $this->hasMany(QuestionOptions::class, 'q_id', 'id')
            ->select(['id', 'q_id', 'content', 'is_success']);
    }
}
