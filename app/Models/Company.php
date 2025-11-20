<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Company extends Model
{
    use HasFactory;

    protected $fillable = [
        'owner_id',
        'name',
        'slug',
        'headline',
        'website',
        'logo_url',
        'size',
        'industry',
        'about',
        'hiring_regions',
        'tech_stack',
        'is_public',
        'highlighted_until',
    ];

    protected $casts = [
        'hiring_regions' => 'array',
        'tech_stack' => 'array',
        'is_public' => 'boolean',
        'highlighted_until' => 'datetime',
    ];

    protected static function booted(): void
    {
        static::creating(function (self $company): void {
            if (! $company->slug) {
                $company->slug = Str::slug($company->name) . '-' . Str::random(6);
            }
        });
    }

    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function jobs(): HasMany
    {
        return $this->hasMany(Job::class);
    }

    public function subscriptions(): HasMany
    {
        return $this->hasMany(Subscription::class);
    }
}

