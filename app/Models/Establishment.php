<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Establishment extends Model
{
    use HasFactory;

    protected $fillable = [
        'es_name_establishment',
        'es_nit',
        'es_phone',
        'es_address',
        'es_commune',
        'es_neighborhood',
        'es_email_establishment',
        'es_station_id',
        'es_us_id',
        'es_ci_id'
    ];

    public function city()
    {
        return $this->belongsTo(City::class, 'es_ci_id');
    }

    public function station()
    {
        return $this->belongsTo(Station::class, 'es_station_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'es_us_id');
    }

    public function inspections()
    {
        return $this->hasMany(Inspection::class, 'in_establishment_id');
    }
}
