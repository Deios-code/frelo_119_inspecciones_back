<?php

namespace App\Models\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ModelInspection extends Model
{
    use HasFactory;

    protected $fillable = [
        'in_state',
        'in_score',
        'in_consecutive',
        'in_inspectors_id',
        'in_establishment_id'
    ];
}
