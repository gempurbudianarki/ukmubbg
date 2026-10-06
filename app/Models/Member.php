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
        'recruitment_id',
        'nim',
        'name',
        'email',
        'phone_number',
        'division_id',
        'batch_year',
        'status',
        'join_date',
        'notes',
    ];

    protected $casts = [
        'join_date' => 'date',
    ];

    public function division(): BelongsTo
    {
        return $this->belongsTo(Division::class);
    }

    public function recruitment(): BelongsTo
    {
        return $this->belongsTo(Recruitment::class);
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
