<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserOptionsAnswer extends Model
{
    protected $table = 'user_options_answer';

    protected $fillable = [
        'uso_file',
        'uso_us_id',
        'uso_op_id',
    ];
}
