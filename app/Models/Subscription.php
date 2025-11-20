<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Subscription extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_id',
        'plan',
        'billing_provider',
        'provider_subscription_id',
        'seats',
        'status',
        'renews_at',
        'ends_at',
        'limits',
    ];

    protected $casts = [
        'renews_at' => 'datetime',
        'ends_at' => 'datetime',
        'limits' => 'array',
    ];

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }
}

