<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserSection extends Model
{
    use HasFactory;

    protected $fillable = [
        'uss_name',
        'uss_usf_id'
    ];
}
