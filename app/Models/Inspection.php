<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Inspection extends Model
{
    use HasFactory;

    protected $fillable = [
        'in_state',
        'in_score',
        'in_consecutive',
        'in_inspectors_id',
        'in_establishment_id'
    ];

    public function inspector()
    {
        return $this->belongsTo(Inspector::class, 'in_inspectors_id');
    }

    public function establishment()
    {
        return $this->belongsTo(Establishment::class, 'in_establishment_id');
    }
}
