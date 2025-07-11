<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserForm extends Model
{
    use HasFactory;

    protected $fillable = [
        'usf_user_id',
        'usf_form_id'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'usf_user_id');
    }

    public function form()
    {
        return $this->belongsTo(Form::class, 'usf_form_id');
    }
}
