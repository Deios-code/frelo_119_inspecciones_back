<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Inspector extends Model
{
    use HasFactory;

    protected $fillable = [
        'ins_range',
        'ins_id_user',
        'ins_id_station'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'ins_id_user');
    }

    public function station()
    {
        return $this->belongsTo(Station::class, 'ins_id_station');
    }

    public function inspections()
    {
        return $this->hasMany(Inspection::class, 'in_inspectors_id');
    }
}
