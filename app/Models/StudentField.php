<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StudentField extends Model
{
    protected $fillable = [
        'key', 'label', 'required', 'type', 'priority', 'options'
    ];
}
