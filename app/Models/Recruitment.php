<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Recruitment extends Model
{
    use HasFactory;

    protected $fillable = [
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
        'file_ktm',
        'file_cv',
        'status',
        'selection_stage',
        'interview_schedule',
        'interview_location',
        'admin_notes',
    ];

    public function firstChoiceDivision()
    {
        return $this->belongsTo(Division::class, 'first_choice_division_id');
    }

    public function secondChoiceDivision()
    {
        return $this->belongsTo(Division::class, 'second_choice_division_id');
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
