<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class JobApplicationResource extends JsonResource
{
    /**
     * @param \App\Models\JobApplication $resource
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'status' => $this->status,
            'cover_letter' => $this->cover_letter,
            'resume_url' => $this->resume_url,
            'viewed_at' => $this->viewed_at,
            'shortlisted_at' => $this->shortlisted_at,
            'rejected_at' => $this->rejected_at,
            'hired_at' => $this->hired_at,
            'meta' => $this->meta,
            'job' => new JobResource($this->whenLoaded('job')),
            'user' => new UserResource($this->whenLoaded('user')),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}

