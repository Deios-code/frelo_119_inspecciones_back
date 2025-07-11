<?php

namespace App\Models\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ModelCity extends Model
{
    use HasFactory;

    protected $fillable = [
        'ci_name',
        'ci_dane',
        'ci_de_id'
    ];

}
