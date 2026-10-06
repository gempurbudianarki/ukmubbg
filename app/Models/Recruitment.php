<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Recruitment extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'registration_code',
        'full_name',
        'nim',
        'email',
        'phone_whatsapp',
        'semester',
        'class_group',
        'first_choice_division_id',
        'second_choice_division_id',
        'reason_to_join',
        'portfolio_url',
        'profile_photo',
        'github_url',
        'file_ktm',
        'file_cv',
        'status',
        'selection_stage',
        'interview_schedule',
        'interview_location',
        'admin_notes',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function firstChoiceDivision()
    {
        return $this->belongsTo(Division::class, 'first_choice_division_id');
    }

    public function secondChoiceDivision()
    {
        return $this->belongsTo(Division::class, 'second_choice_division_id');
    }

    public function member()
    {
        return $this->hasOne(Member::class);
    }

    public function getAvatarUrlAttribute(): string
    {
        if ($this->profile_photo) {
            return asset('storage/' . $this->profile_photo);
        }

        if ($this->user && $this->user->avatar) {
            return $this->user->avatar_url;
        }

        return 'https://ui-avatars.com/api/?name=' . urlencode($this->full_name ?? 'Peserta') . '&background=0284c7&color=ffffff&bold=true';
    }

    public static function generateCode(): string
    {
        do {
            $code = 'UKM-' . date('Y') . '-' . strtoupper(Str::random(5));
        } while (self::where('registration_code', $code)->exists());

        return $code;
    }

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'pending' => 'Menunggu Verifikasi',
            'interview' => 'Tahap Wawancara',
            'accepted' => 'Diterima',
            'rejected' => 'Tidak Lolos',
            default => ucfirst($this->status),
        };
    }

    public function getStatusBadgeClassAttribute(): string
    {
        return match ($this->status) {
            'pending' => 'badge-warning',
            'interview' => 'badge-info',
            'accepted' => 'badge-success',
            'rejected' => 'badge-danger',
            default => 'badge-secondary',
        };
    }
}
