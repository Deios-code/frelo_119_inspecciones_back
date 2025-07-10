<?php

namespace App\Models\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ModelUserSections extends Model
{
    use HasFactory;

    protected $fillable = [
        'uss_name',
        'uss_usf_id'
    ];
}
