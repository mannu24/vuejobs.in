<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'headline',
        'about',
        'location',
        'timezone',
        'linkedin_url',
        'github_url',
        'website_url',
        'portfolio_url',
        'resume_url',
        'avatar_url',
        'skill_tags',
        'is_available',
        'resume_visibility',
        'settings',
        'last_active_at',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'last_active_at' => 'datetime',
            'skill_tags' => 'array',
            'settings' => 'array',
            'is_available' => 'boolean',
            'password' => 'hashed',
        ];
    }

    public function companies()
    {
        return $this->hasMany(Company::class, 'owner_id');
    }

    public function jobs()
    {
        return $this->hasMany(Job::class, 'creator_id');
    }

    public function applications()
    {
        return $this->hasMany(JobApplication::class);
    }

    public function alerts()
    {
        return $this->hasMany(JobAlert::class);
    }

    public function resumes()
    {
        return $this->hasMany(Resume::class);
    }

    public function sentMessages()
    {
        return $this->hasMany(Message::class, 'sender_id');
    }

    public function receivedMessages()
    {
        return $this->hasMany(Message::class, 'receiver_id');
    }
}
