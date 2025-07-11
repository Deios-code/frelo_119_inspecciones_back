<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Form extends Model
{
    use HasFactory;

    protected $fillable = [
        'fo_name',
        'fo_type',
        'fo_score',
        'fo_edit',
        'fo_process_id'
    ];

    public function process()
    {
        return $this->belongsTo(Process::class, 'fo_process_id');
    }
}
