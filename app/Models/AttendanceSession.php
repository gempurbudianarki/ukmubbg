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
        'passcode',
        'passcode_expires_at',
        'allow_self_checkin',
    ];

    protected $casts = [
        'session_date' => 'date',
        'passcode_expires_at' => 'datetime',
        'allow_self_checkin' => 'boolean',
    ];

    public function isPasscodeExpired(): bool
    {
        if (!$this->passcode_expires_at) {
            return false;
        }

        return now()->gt($this->passcode_expires_at);
    }

    public static function generatePasscode(): string
    {
        $chars = 'ABCDEFGHJKLMNPQRSTUVWXYZ23456789';
        $code = '';
        for ($i = 0; $i < 6; $i++) {
            $code .= $chars[random_int(0, strlen($chars) - 1)];
        }
        return $code;
    }

    public function isOpen(): bool
    {
        return $this->status === 'open';
    }

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
