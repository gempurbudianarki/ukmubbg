<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Event extends Model
{
    use HasFactory;

    protected $fillable = [
        'division_id',
        'title',
        'slug',
        'description',
        'banner_image',
        'event_date',
        'time_start',
        'time_end',
        'location_type',
        'location_venue',
        'registration_link',
        'max_participants',
        'status',
    ];

    protected $casts = [
        'event_date' => 'date',
    ];

    public function division(): BelongsTo
    {
        return $this->belongsTo(Division::class);
    }

    public function getBannerUrlAttribute(): string
    {
        if ($this->banner_image) {
            if (str_starts_with($this->banner_image, 'http') || str_starts_with($this->banner_image, 'images/')) {
                return asset($this->banner_image);
            }
            return asset('storage/' . $this->banner_image);
        }

        $slug = $this->division?->slug ?? '';
        if (str_contains($slug, 'multi') || str_contains($slug, 'desain')) {
            return asset('images/project_multimedia.jpg');
        } elseif (str_contains($slug, 'cyber') || str_contains($slug, 'sec')) {
            return asset('images/project_cyber.jpg');
        } elseif (str_contains($slug, 'iot') || str_contains($slug, 'robot') || str_contains($slug, 'hardware')) {
            return asset('images/project_iot.jpg');
        }

        return asset('images/event_hackathon.jpg');
    }
}
