<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'user' => [
                'email' => $this->email()->value(),
                'token' => $this->rememberToken()->value(),
                'username' => $this->username()->value(),
                'bio' => $this->bio()? $this->bio()->value() : null,
                'image' => $this->image()? $this->image()->value() : null,
            ]
        ];
        // return parent::toArray($request);
    }
}
