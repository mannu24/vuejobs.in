<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class JobAlertResource extends JsonResource
{
    /**
     * @param \App\Models\JobAlert $resource
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'filters' => $this->filters,
            'frequency' => $this->frequency,
            'last_sent_at' => $this->last_sent_at,
            'created_at' => $this->created_at,
        ];
    }
}

