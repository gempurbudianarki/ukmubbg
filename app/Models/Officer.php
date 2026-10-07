<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Officer extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'nim',
        'period',
        'department_level',
        'position',
        'photo',
        'social_links',
        'sort_order',
    ];

    protected $casts = [
        'social_links' => 'array',
        'sort_order' => 'integer',
    ];

    public function getDepartmentLabelAttribute(): string
    {
        $posLower = strtolower($this->position);
        $isDosen = str_contains($posLower, 'pembina') || str_contains($posLower, 'pembimbing') || str_contains($posLower, 'dosen');

        if ($this->department_level === 'bph') {
            if ($isDosen) {
                return 'Dosen Pembina Utama UKM';
            }
            if (str_contains($posLower, 'ketua umum') || str_contains($posLower, 'ketua ukm')) {
                return 'Pimpinan / Ketua Umum';
            }
            if (str_contains($posLower, 'wakil')) {
                return 'Pimpinan / Wakil Ketua';
            }
            return 'BPH / Pengurus Inti';
        }

        $divName = match ($this->department_level) {
            'pemrograman' => 'Pemrograman',
            'multimedia' => 'Multimedia',
            'iot' => 'IoT',
            'cyber' => 'Cyber Security',
            default => strtoupper($this->department_level),
        };

        if ($isDosen) {
            return "Dosen Pembimbing {$divName}";
        }

        if (str_contains($posLower, 'koordinator') || str_contains($posLower, 'ketua')) {
            return "Koordinator {$divName}";
        }

        return "Divisi {$divName}";
    }

    public function getBadgeStyleAttribute(): array
    {
        $posLower = strtolower($this->position);
        $isDosen = str_contains($posLower, 'pembina') || str_contains($posLower, 'pembimbing') || str_contains($posLower, 'dosen');

        if ($this->department_level === 'bph') {
            if ($isDosen) {
                return [
                    'bg' => '#fef3c7',
                    'color' => '#b45309',
                    'border' => '#fde68a',
                    'icon' => 'fa-chalkboard-user',
                ];
            }
            if (str_contains($posLower, 'ketua umum') || str_contains($posLower, 'ketua ukm')) {
                return [
                    'bg' => '#fee2e2',
                    'color' => '#b91c1c',
                    'border' => '#fecaca',
                    'icon' => 'fa-crown',
                ];
            }
            if (str_contains($posLower, 'wakil')) {
                return [
                    'bg' => '#ffedd5',
                    'color' => '#c2410c',
                    'border' => '#fed7aa',
                    'icon' => 'fa-user-tie',
                ];
            }
            return [
                'bg' => '#e0e7ff',
                'color' => '#4338ca',
                'border' => '#c7d2fe',
                'icon' => 'fa-users-gear',
            ];
        }

        if ($isDosen) {
            return [
                'bg' => '#fef9c3',
                'color' => '#a16207',
                'border' => '#fef08a',
                'icon' => 'fa-chalkboard-user',
            ];
        }

        return match ($this->department_level) {
            'pemrograman' => [
                'bg' => '#e0f2fe',
                'color' => '#0284c7',
                'border' => '#bae6fd',
                'icon' => 'fa-code',
            ],
            'multimedia' => [
                'bg' => '#f3e8ff',
                'color' => '#7e22ce',
                'border' => '#e9d5ff',
                'icon' => 'fa-palette',
            ],
            'iot' => [
                'bg' => '#fef3c7',
                'color' => '#d97706',
                'border' => '#fde68a',
                'icon' => 'fa-microchip',
            ],
            'cyber' => [
                'bg' => '#ecfdf5',
                'color' => '#047857',
                'border' => '#a7f3d0',
                'icon' => 'fa-shield-halved',
            ],
            default => [
                'bg' => '#f1f5f9',
                'color' => '#475569',
                'border' => '#cbd5e1',
                'icon' => 'fa-user',
            ],
        };
    }
}
