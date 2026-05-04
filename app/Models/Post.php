<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Post extends Model
{
    use HasFactory;

    protected $fillable = [
        'title', 'slug', 'excerpt', 'content',
        'category_id', 'author_id', 'published_at',
    ];

    protected $casts = [
        'published_at' => 'datetime',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function author()
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    public function scopePublished(Builder $query): Builder
    {
        return $query->whereNotNull('published_at')->where('published_at', '<=', now());
    }

    public function scopeSearch(Builder $query, ?string $term): Builder
    {
        if (!$term) return $query;
        return $query->where(function ($q) use ($term) {
            $q->where('title', 'like', "%{$term}%")
              ->orWhere('excerpt', 'like', "%{$term}%")
              ->orWhere('content', 'like', "%{$term}%");
        });
    }

    public function scopeByCategory(Builder $query, ?string $slug): Builder
    {
        if (!$slug || $slug === 'semua') return $query;
        return $query->whereHas('category', fn($q) => $q->where('slug', $slug));
    }
}
