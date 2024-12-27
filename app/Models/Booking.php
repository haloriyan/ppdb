<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    protected $fillable = [
        'student_id', 'wave_id', 'coupon_id', 'total_pay',
        'payment_status', 'payment_method', 'payment_channel', 'payment_payload',
        'is_accepted'
    ];

    public function wave() {
        return $this->belongsTo(Wave::class, 'wave_id');
    }
    public function coupon() {
        return $this->belongsTo(Coupon::class, 'coupon_id');
    }
}
