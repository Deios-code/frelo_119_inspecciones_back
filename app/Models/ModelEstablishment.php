<?php

namespace App\Models\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ModelEstablishment extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'es_name_establishment',
        'es_nit',
        'es_phone',
        'es_department',
        'es_address',
        'es_commune',
        'es_neighborhood',
        'es_email_establishment',
        'es_station_id',
        'es_us_id',
        'es_ci_id'
    ];
}
