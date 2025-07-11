<?php

namespace App\Models\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ModelStation extends Model
{
    use HasFactory;

    protected $fillable = ['st_name', 'st_nit', 'st_address', 'st_phone', 'st_user_id', 'st_ci_id'];
}
