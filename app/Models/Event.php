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
        return match ($slug) {
            'pemrograman' => asset('images/event_hackathon.jpg'),
            'multimedia' => asset('images/project_multimedia.jpg'),
            'cyber-security' => asset('images/project_cyber.jpg'),
            'iot' => asset('images/project_iot.jpg'),
            default => asset('images/event_hackathon.jpg'),
        };
    }
}
