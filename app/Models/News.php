<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class News extends Model
{
    protected $fillable = [
        'admin_id', 'title', 'slug', 'content', 'featured_image', 'hit', 'status',
    ];

    public function admin() {
        return $this->belongsTo(Admin::class, 'admin_id');
    }
}
