<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class JobApplication extends Model
{
    use HasFactory;

    protected $fillable = [
        'job_id',
        'user_id',
        'cover_letter',
        'resume_url',
        'status',
        'viewed_at',
        'shortlisted_at',
        'rejected_at',
        'hired_at',
        'meta',
    ];

    protected $casts = [
        'viewed_at' => 'datetime',
        'shortlisted_at' => 'datetime',
        'rejected_at' => 'datetime',
        'hired_at' => 'datetime',
        'meta' => 'array',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function job(): BelongsTo
    {
        return $this->belongsTo(Job::class);
    }
}

