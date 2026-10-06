<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AttendanceSession extends Model
{
    use HasFactory;

    protected $fillable = [
        'division_id',
        'title',
        'day_name',
        'session_date',
        'time_start',
        'time_end',
        'session_type',
        'location',
        'topic_material',
        'learning_outcomes',
        'instructor_name',
        'created_by',
        'notes',
        'status',
    ];

    protected $casts = [
        'session_date' => 'date',
    ];

    public function division(): BelongsTo
    {
        return $this->belongsTo(Division::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function logs(): HasMany
    {
        return $this->hasMany(AttendanceLog::class, 'session_id');
    }

    public function getAttendanceRateAttribute(): float
    {
        $total = $this->logs()->count();
        if ($total === 0) {
            return 0.0;
        }
        $hadir = $this->logs()->where('status', 'hadir')->count();
        return round(($hadir / $total) * 100, 1);
    }
}
