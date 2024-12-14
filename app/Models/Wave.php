<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Wave extends Model
{
    protected $fillable = [
        'name', 'price', 'start_date', 'end_date', 'start_quantity', 'quantity'
    ];
}
