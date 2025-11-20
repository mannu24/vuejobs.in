<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MessageResource extends JsonResource
{
    /**
     * @param \App\Models\Message $resource
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'body' => $this->body,
            'attachments' => $this->attachments ?? [],
            'read_at' => $this->read_at,
            'job' => new JobResource($this->whenLoaded('job')),
            'sender' => new UserResource($this->whenLoaded('sender')),
            'receiver' => new UserResource($this->whenLoaded('receiver')),
            'created_at' => $this->created_at,
        ];
    }
}

