<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class JobAlert extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'filters',
        'frequency',
        'last_sent_at',
    ];

    protected $casts = [
        'filters' => 'array',
        'last_sent_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}

