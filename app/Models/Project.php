<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Project extends Model
{
    use HasFactory;

    protected $fillable = [
        'division_id',
        'user_id',
        'title',
        'slug',
        'description',
        'author_names',
        'tech_stack',
        'demo_url',
        'repo_url',
        'thumbnail',
        'is_featured',
        'submission_status',
    ];

    protected $casts = [
        'tech_stack' => 'array',
        'is_featured' => 'boolean',
    ];

    public function division(): BelongsTo
    {
        return $this->belongsTo(Division::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function scopePublished($query)
    {
        return $query->where('submission_status', 'published');
    }

    public function getThumbnailUrlAttribute(): string
    {
        if ($this->thumbnail) {
            if (str_starts_with($this->thumbnail, 'http') || str_starts_with($this->thumbnail, 'images/')) {
                return asset($this->thumbnail);
            }
            return asset('storage/' . $this->thumbnail);
        }

        $slug = $this->division?->slug ?? '';
        return match ($slug) {
            'pemrograman' => asset('images/project_web.jpg'),
            'multimedia' => asset('images/project_multimedia.jpg'),
            'iot' => asset('images/project_iot.jpg'),
            'cyber-security' => asset('images/project_cyber.jpg'),
            default => asset('images/project_web.jpg'),
        };
    }
}
