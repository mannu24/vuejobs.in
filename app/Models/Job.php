<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Job extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'external_id',
        'hash',
        'company_id',
        'creator_id',
        'title',
        'slug',
        'department',
        'location_type',
        'location',
        'country',
        'timezone',
        'contract_type',
        'experience_level',
        'vue_version',
        'nuxt_version',
        'requires_typescript',
        'salary_min',
        'salary_max',
        'currency',
        'salary_interval',
        'status',
        'published_at',
        'expires_at',
        'featured_until',
        'skills',
        'benefits',
        'description',
        'apply_url',
        'source',
        'source_url',
        'is_verified',
        'company_name',
    ];

    protected $casts = [
        'requires_typescript' => 'boolean',
        'is_verified' => 'boolean',
        'skills' => 'array',
        'benefits' => 'array',
        'published_at' => 'datetime',
        'expires_at' => 'datetime',
        'featured_until' => 'datetime',
    ];

    protected static function booted(): void
    {
        static::creating(function (self $job): void {
            if (! $job->slug) {
                $job->slug = Str::slug($job->title) . '-' . Str::random(6);
            }
        });
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'creator_id');
    }

    public function applications(): HasMany
    {
        return $this->hasMany(JobApplication::class);
    }

    public function savedByUsers(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'job_saves');
    }

    public function scopePublished(Builder $query): Builder
    {
        return $query->where('status', 'published');
    }
}

