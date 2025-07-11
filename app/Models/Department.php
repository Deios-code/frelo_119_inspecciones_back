<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    use HasFactory;

    protected $fillable = [
        'de_name',
        'de_dane'
    ];

    public function cities()
    {
        return $this->hasMany(City::class, 'ci_de_id');
    }
}
