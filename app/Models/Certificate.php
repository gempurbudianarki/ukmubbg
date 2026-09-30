<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Certificate extends Model
{
    use HasFactory;

    protected $fillable = [
        'certificate_code',
        'recipient_name',
        'recipient_nim',
        'recipient_email',
        'event_name',
        'role_as',
        'issue_date',
        'file_path',
    ];

    protected $casts = [
        'issue_date' => 'date',
    ];
}
