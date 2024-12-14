<?php

namespace App\Models;

use Illuminate\Auth\Middleware\Authenticate;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Student extends Authenticatable
{
    use Notifiable;

    protected $fillable = [
        'name', 'phone', 'fields'
    ];

    public function booking() {
        return $this->hasOne(Booking::class, 'student_id');
    }
}
