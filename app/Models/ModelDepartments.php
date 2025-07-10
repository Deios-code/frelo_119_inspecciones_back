<?php

namespace App\Models\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ModelDepartment extends Model
{
    use HasFactory;

    protected $fillable = [
        'de_name',
        'de_dane'
    ];

}
