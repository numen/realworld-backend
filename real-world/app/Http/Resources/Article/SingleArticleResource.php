<?php

namespace App\Http\Resources\Article;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SingleArticleResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'slug' => $this->slug,
            'title' => $this->title,
            'description' => $this->description,
            'body' => $this->body,
            'tagList' => $this->title,
            'createdAt' => $this->created_at,
            'updatedAt' => $this->updated_at,
            'favorited' => (bool)$this->is_favorited,
            'favoritesCount' => $this->favorites_count,
            'author' => [
                'username' => $this->author_username,
                'bio' => $this->author_bio,
                'image' => $this->author_image,
                'following' => (bool)$this->author_is_following,
            ],
        ];
    }
}
