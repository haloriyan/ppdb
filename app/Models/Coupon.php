<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    protected $fillable = [
        'wave_id', 'code', 'type', 'amount', 'valid_until', 'quantity', 'start_quantity', 'is_active'
    ];

    public function wave() {
        return $this->belongsTo(Wave::class, 'wave_id');
    }
}
