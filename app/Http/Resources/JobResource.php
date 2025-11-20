<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class JobResource extends JsonResource
{
    /**
     * @param \App\Models\Job $resource
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'slug' => $this->slug,
            'department' => $this->department,
            'location_type' => $this->location_type,
            'location' => $this->location,
            'country' => $this->country,
            'timezone' => $this->timezone,
            'contract_type' => $this->contract_type,
            'experience_level' => $this->experience_level,
            'vue_version' => $this->vue_version,
            'nuxt_version' => $this->nuxt_version,
            'requires_typescript' => $this->requires_typescript,
            'salary_min' => $this->salary_min,
            'salary_max' => $this->salary_max,
            'currency' => $this->currency,
            'salary_interval' => $this->salary_interval,
            'status' => $this->status,
            'published_at' => $this->published_at,
            'expires_at' => $this->expires_at,
            'featured_until' => $this->featured_until,
            'skills' => $this->skills ?? [],
            'benefits' => $this->benefits ?? [],
            'description' => $this->description,
            'company' => new CompanyResource($this->whenLoaded('company')),
            'creator' => new UserResource($this->whenLoaded('creator')),
            'applications_count' => $this->whenCounted('applications'),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}

