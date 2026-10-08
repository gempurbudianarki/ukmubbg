<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Division extends Model
{
    use HasFactory;

    protected $fillable = [
        'slug',
        'name',
        'tagline',
        'description',
        'focus_topics',
        'icon_svg',
        'color_accent',
        'banner_image',
        'vision',
        'mission',
        'adviser_name',
        'adviser_title',
        'adviser_photo',
        'leader_name',
        'leader_nim',
        'leader_photo',
        'leader_bio',
        'social_links',
        'is_recruitment_open',
        'recruitment_quota',
        'recruitment_notes',
    ];

    protected $casts = [
        'focus_topics' => 'array',
        'social_links' => 'array',
        'is_recruitment_open' => 'boolean',
        'recruitment_quota' => 'integer',
    ];

    public function getRemainingQuotaAttribute(): ?int
    {
        if ($this->recruitment_quota === null) {
            return null;
        }
        $count = $this->first_choice_applicants_count ?? $this->firstChoiceApplicants()->count();
        return max(0, (int)$this->recruitment_quota - (int)$count);
    }

    public function getIsQuotaFullAttribute(): bool
    {
        if ($this->recruitment_quota === null) {
            return false;
        }
        $count = $this->first_choice_applicants_count ?? $this->firstChoiceApplicants()->count();
        return (int)$count >= (int)$this->recruitment_quota;
    }

    public function getFocusTopicsListAttribute(): array
    {
        if (is_array($this->focus_topics)) {
            return $this->focus_topics;
        }
        return json_decode($this->focus_topics ?: '[]', true) ?: [];
    }

    public function getSocialLinksListAttribute(): array
    {
        if (is_array($this->social_links)) {
            return $this->social_links;
        }
        return json_decode($this->social_links ?: '{}', true) ?: [];
    }

    public function posts()
    {
        return $this->hasMany(Post::class);
    }

    public function admins()
    {
        return $this->hasMany(User::class);
    }

    public function firstChoiceApplicants()
    {
        return $this->hasMany(Recruitment::class, 'first_choice_division_id');
    }

    public function secondChoiceApplicants()
    {
        return $this->hasMany(Recruitment::class, 'second_choice_division_id');
    }

    public function projects()
    {
        return $this->hasMany(Project::class);
    }

    public function events()
    {
        return $this->hasMany(Event::class);
    }

    public function members()
    {
        return $this->hasMany(Member::class);
    }

    public function attendanceSessions()
    {
        return $this->hasMany(AttendanceSession::class);
    }
}
