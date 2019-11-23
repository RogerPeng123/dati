<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class QuestionOptions extends Model
{
    use SoftDeletes;

    protected $table = 'question_options';

    protected $primaryKey = 'id';
}
