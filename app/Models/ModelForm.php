<?php

namespace App\Models\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ModelForm extends Model
{
    use HasFactory;

    protected $fillable = [
        'fo_name',
        'fo_type',
        'fo_score',
        'fo_edit',
        'fo_process_id'
    ];

}
