<?php

namespace App\Models\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ModelOptionAnswer extends Model
{
    use HasFactory;

    protected $fillable = [
        'op_text',
        'op_correct',
        'op_file',
        'op_qu_id',
        'op_usq_id'

    ];

}
