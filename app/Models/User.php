<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'us_name',
        'us_role',
        'us_last_name',
        'us_type_document',
        'us_document',
        'us_birthday',
        'us_ci_id',
        'us_address',
        'us_phone',
        'us_habeas_data',
        'us_exoneration',
        'us_email',
        'us_password'
    ];

    protected $hidden = [
        'us_password'
    ];

    protected $casts = [
        'us_password' => 'hashed'
    ];

    public function establishment()
    {
        return $this->hasMany(Establishment::class, 'es_us_id');
    }

    public function stations()
    {
        return $this->hasOne(Station::class, 'st_user_id');
    }

    public function sections()
    {
        return $this->hasMany(Section::class, 'se_user_id');
    }

    public function city()
    {
        return $this->belongsTo(City::class, 'us_ci_id');
    }

    public function inspector()
    {
        return $this->hasOne(Inspector::class, 'ins_id_user');
    }

}
