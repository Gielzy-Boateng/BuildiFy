<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Theme extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'primary_color',
        'secondary_color',
        'background_color',
        'text_color',
        'accent_color',
        'preview_image'
    ];

    public function pages()
    {
        return $this->hasMany(Page::class);
    }
}
