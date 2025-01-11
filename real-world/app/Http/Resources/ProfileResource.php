<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProfileResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'profile' => [
                'username' => $this->username()->value(),
                'bio' => $this->bio()? $this->bio()->value() : null,
                'image' => $this->image()? $this->image()->value() : null,
                'following' => $this->following(),
            ]
        ];
    }
}
