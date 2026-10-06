<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Member extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'recruitment_id',
        'nim',
        'name',
        'email',
        'phone_number',
        'avatar',
        'division_id',
        'batch_year',
        'status',
        'join_date',
        'notes',
    ];

    protected $casts = [
        'join_date' => 'date',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function division(): BelongsTo
    {
        return $this->belongsTo(Division::class);
    }

    public function recruitment(): BelongsTo
    {
        return $this->belongsTo(Recruitment::class);
    }

    public function getAvatarUrlAttribute(): string
    {
        if ($this->avatar) {
            return asset('storage/' . $this->avatar);
        }

        if ($this->user && $this->user->avatar) {
            return $this->user->avatar_url;
        }

        if ($this->recruitment && $this->recruitment->profile_photo) {
            return asset('storage/' . $this->recruitment->profile_photo);
        }

        return 'https://ui-avatars.com/api/?name=' . urlencode($this->name ?? 'Anggota') . '&background=0284c7&color=ffffff&bold=true';
    }

    public function attendanceLogs(): HasMany
    {
        return $this->hasMany(AttendanceLog::class);
    }

    public function getStatusBadgeAttribute(): string
    {
        return match ($this->status) {
            'aktif' => 'badge-success',
            'alumni' => 'badge-info',
            'non_aktif' => 'badge-neutral',
            default => 'badge-neutral',
        };
    }
}
