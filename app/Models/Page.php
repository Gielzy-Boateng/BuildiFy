<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Page extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'slug',
        'title',
        'content',
        'theme_id',
        'logo',
        'hero_image',
        'is_published',
        'published_at'
    ];

    protected $casts = [
        'content' => 'array',
        'is_published' => 'boolean',
        'published_at' => 'datetime',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($page) {
            if (empty($page->slug)) {
                $page->slug = Str::slug($page->title) . '-' . Str::random(6);
            }
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function theme()
    {
        return $this->belongsTo(Theme::class);
    }

    public function pageViews()
    {
        return $this->hasMany(PageView::class);
    }

    public function versions()
    {
        return $this->hasMany(PageVersion::class);
    }

    // Analytics helper methods
    public function getTotalViews()
    {
        return $this->pageViews()->count();
    }

    public function getUniqueViews()
    {
        return $this->pageViews()->where('is_unique_visitor', true)->count();
    }

    public function getDailyViews($date = null)
    {
        $date = $date ?? today();
        return $this->pageViews()
            ->whereDate('visited_at', $date)
            ->count();
    }

    public function getWeeklyViews()
    {
        return $this->pageViews()
            ->whereBetween('visited_at', [now()->startOfWeek(), now()->endOfWeek()])
            ->count();
    }

    public function getMonthlyViews()
    {
        return $this->pageViews()
            ->whereMonth('visited_at', now()->month)
            ->whereYear('visited_at', now()->year)
            ->count();
    }
}
