<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PageView extends Model
{
    use HasFactory;

    protected $fillable = [
        'page_id',
        'ip_address',
        'user_agent',
        'session_id',
        'is_unique_visitor',
        'visited_at'
    ];

    protected $casts = [
        'visited_at' => 'datetime',
        'is_unique_visitor' => 'boolean',
    ];

    public function page()
    {
        return $this->belongsTo(Page::class);
    }
}
