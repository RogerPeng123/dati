<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class QuestionOptions extends Model
{
    use SoftDeletes;

    const SUCCESS_OPTIONS = 1;
    const ERROR_OPTIONS = 0;

    protected $fillable = ['*'];

    protected $table = 'question_options';

    protected $primaryKey = 'id';
}
