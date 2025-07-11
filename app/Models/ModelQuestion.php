<?php

namespace App\Models\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ModelQuestion extends Model
{
    use HasFactory;

    protected $fillable = [
        'qu_statement',
        'qu_score',
        'qu_nature',
        'qu_type',
        'qu_required',
        'qu_se_id'
    ];

}
