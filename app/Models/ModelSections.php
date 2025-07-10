<?php

namespace App\Models\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ModelSection extends Model
{
    use HasFactory;

    protected $fillable = [
        'se_name',
        'se_percentage',
        'se_fo_id'
    ];

}
