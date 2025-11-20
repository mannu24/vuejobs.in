<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Resume extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'file_url',
        'headline',
        'summary',
        'experience',
        'education',
        'projects',
        'certifications',
        'visibility',
    ];

    protected $casts = [
        'experience' => 'array',
        'education' => 'array',
        'projects' => 'array',
        'certifications' => 'array',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}

