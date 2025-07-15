<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Station extends Model
{
    use HasFactory;

    protected $fillable = [
        'st_name',
        'st_nit',
        'st_address',
        'st_phone',
        'st_longitude',
        'st_latitude',
        'st_user_id',
        'st_ci_id'
    ];

    public function city()
    {
        return $this->belongsTo(City::class, 'st_ci_id');
    }

    public function establishments()
    {
        return $this->hasMany(Establishment::class, 'es_station_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'st_user_id');
    }

    public function inspectors()
    {
        return $this->hasMany(Inspector::class, 'ins_id_station');
    }
}
