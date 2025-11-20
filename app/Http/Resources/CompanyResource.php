<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CompanyResource extends JsonResource
{
    /**
     * @param \App\Models\Company $resource
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'slug' => $this->slug,
            'headline' => $this->headline,
            'website' => $this->website,
            'logo_url' => $this->logo_url,
            'size' => $this->size,
            'industry' => $this->industry,
            'about' => $this->about,
            'hiring_regions' => $this->hiring_regions ?? [],
            'tech_stack' => $this->tech_stack ?? [],
            'is_public' => $this->is_public,
            'highlighted_until' => $this->highlighted_until,
            'owner' => new UserResource($this->whenLoaded('owner')),
            'jobs_count' => $this->whenCounted('jobs'),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}

