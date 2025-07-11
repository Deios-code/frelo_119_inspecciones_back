<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    use HasFactory;

    protected $table = 'cities';

    protected $fillable = [
        'ci_name',
        'ci_dane',
        'ci_de_id'
    ];

    public function department()
    {
        return $this->belongsTo(Department::class, 'ci_de_id');
    }

    public function establishments()
    {
        return $this->hasMany(Establishment::class, 'es_ci_id');
    }

    public function stations()
    {
        return $this->hasOne(Station::class, 'st_ci_id');
    }

    public function users()
    {
        return $this->hasMany(User::class, 'us_ci_id');
    }
}
