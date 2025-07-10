<?php

namespace App\Models\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ModelUserOptionAnswer extends Model
{
    use HasFactory;

    protected $fillable = [
        'uso_us_id',
        'uso_op_id'
    ];

}
