<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Gallery extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'category',
        'image_path',
        'caption',
        'event_date',
    ];

    protected $casts = [
        'event_date' => 'date',
    ];

    public function getImageUrlAttribute(): string
    {
        if ($this->image_path) {
            if (str_starts_with($this->image_path, 'http') || str_starts_with($this->image_path, 'images/')) {
                return asset($this->image_path);
            }
            if (file_exists(public_path('storage/' . $this->image_path))) {
                return asset('storage/' . $this->image_path);
            }
        }

        return match ($this->category) {
            'Prestasi' => asset('images/project_cyber.jpg'),
            'Workshop' => asset('images/project_iot.jpg'),
            'Kunjungan' => asset('images/project_web.jpg'),
            default => asset('images/gallery_students.jpg'),
        };
    }
}
