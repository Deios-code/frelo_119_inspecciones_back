<?php

namespace App\Models\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ModelUser extends Model
{
    use HasFactory;

    protected $fillable = [
        'us_name',
        'us_last_name',
        'us_type_document',
        'us_document',
        'us_address',
        'us_phone',
        'us_habeas_data',
        'us_exoneration',
        'us_email',
        'us_password',
        'us_establishment_id'
    ];

    
}
