<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Process extends Model
{
    use HasFactory;

    protected $fillable = [
        'pr_name'
    ];

    public function forms()
    {
        return $this->hasMany(Form::class, 'fo_process_id');
    }
}
