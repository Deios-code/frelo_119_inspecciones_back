<?php

namespace App\Models\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ModelUserQuestion extends Model
{
    use HasFactory;

    protected $fillable = [
        'usq_statement',
        'usq_type',
        'usq_uss_id',
        'usq_usf_id',
        'usq_se_id'
    ];

}
