<?php

namespace App\Models\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ModelUserForm extends Model
{
    use HasFactory;

    protected $fillable = [
        'usf_user_id',
        'usf_form_id'
    ];

}
