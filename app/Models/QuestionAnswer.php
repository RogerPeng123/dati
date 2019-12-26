<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class QuestionAnswer extends Model
{
    use SoftDeletes;

    protected $table = 'question_answer';

    protected $primaryKey = 'id';

    public function questionCycle()
    {
        return $this->hasOne(Cycles::class, 'id', 'qc_id');
    }

}
