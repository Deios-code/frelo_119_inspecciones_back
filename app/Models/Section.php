<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Section extends Model
{
    use HasFactory;

    protected $fillable = [
        'se_name',
        'se_percentage',
        'se_fo_id'
    ];

    public function form()
    {
        return $this->belongsTo(Form::class, 'se_fo_id');
    }

    public function questions()
    {
        return $this->hasMany(Question::class, 'qu_se_id');
    }

}
