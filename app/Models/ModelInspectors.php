<?php

namespace App\Models\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ModelInspector extends Model
{
    use HasFactory;

    protected $fillable = [
        'ins_range',
        'ins_id_user',
        'ins_id_station'
    ];

}
