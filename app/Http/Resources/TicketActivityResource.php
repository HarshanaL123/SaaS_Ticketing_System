<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TicketActivityResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'type' => $this->type,
            'old_value' => $this->old_value,
            'new_value' => $this->new_value,
            'comment' => $this->comment,

            // The Actor only load if it is quaried, to prevent N+1 query database crashes.
            'actor' => $this->whenLoaded('actor', fn () => [
                'id' => $this->actor->id,
                'name' => $this->actor->name,
            ]),
            'created_at' => $this->created_at->diffForHumans(),
        ];
    }
}
