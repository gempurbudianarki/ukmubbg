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
}
