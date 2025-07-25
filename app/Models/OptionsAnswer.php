<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OptionsAnswer extends Model
{
    use HasFactory;

    protected $fillable = [
        'op_text',
        'op_correct',
        'op_file',
        'op_qu_id',
        'op_usq_id'
    ];

    public function question()
    {
        return $this->belongsTo(UserQuestion::class, 'op_qu_id');
    }

    public function userOptionsAnswers()
    {
        return $this->hasMany(UserOptionsAnswer::class, 'uso_op_id');
    }
}
