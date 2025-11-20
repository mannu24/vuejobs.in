<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * @param \App\Models\User $resource
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'role' => $this->role,
            'headline' => $this->headline,
            'about' => $this->about,
            'location' => $this->location,
            'timezone' => $this->timezone,
            'links' => [
                'linkedin' => $this->linkedin_url,
                'github' => $this->github_url,
                'website' => $this->website_url,
                'portfolio' => $this->portfolio_url,
            ],
            'resume_url' => $this->resume_url,
            'avatar_url' => $this->avatar_url,
            'skill_tags' => $this->skill_tags ?? [],
            'is_available' => $this->is_available,
            'resume_visibility' => $this->resume_visibility,
            'settings' => $this->settings,
            'email_verified_at' => $this->email_verified_at,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}

