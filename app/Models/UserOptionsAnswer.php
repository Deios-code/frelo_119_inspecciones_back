<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserOptionsAnswer extends Model
{
    use HasFactory;

    protected $fillable = [
        'uso_us_id',
        'uso_op_id'
    ];
}
