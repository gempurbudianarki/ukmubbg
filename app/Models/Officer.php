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
}
