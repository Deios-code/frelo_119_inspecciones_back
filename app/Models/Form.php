<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Form extends Model
{

    protected $table = 'forms';

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

    public function sections()
    {
        return $this->hasMany(Section::class, 'se_fo_id');
    }
}
