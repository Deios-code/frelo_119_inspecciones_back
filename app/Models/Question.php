<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Question extends Model
{

    protected $table = 'questions';

    protected $fillable = [
        'qu_statement',
        'qu_score',
        'qu_nature',
        'qu_type',
        'qu_required',
        'qu_se_id'
    ];

    public function section()
    {
        return $this->belongsTo(Section::class, 'qu_se_id');
    }

    public function userQuestions()
    {
        return $this->hasMany(UserQuestion::class, 'usq_qu_id');
    }

    public function optionsAnswers()
    {
        return $this->hasMany(OptionsAnswer::class, 'op_qu_id');
    }

}
