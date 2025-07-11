<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserQuestion extends Model
{
    use HasFactory;

    protected $fillable = [
        'usq_statement',
        'usq_type',
        'usq_uss_id',
        'usq_usf_id',
        'usq_se_id'
    ];

    public function question()
    {
        return $this->belongsTo(Question::class, 'usq_qu_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'usq_us_id');
    }

    public function section()
    {
        return $this->belongsTo(Section::class, 'usq_se_id');
    }
}
