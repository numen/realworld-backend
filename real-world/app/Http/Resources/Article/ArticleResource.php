<?php

namespace App\Http\Resources\Article;

use App\Http\Resources\UserResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ArticleResource extends JsonResource
{
    //public static $wrap = 'article';
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        /*
        return [
            'slug' => $this->slug,
            'title' => $this->title,
            'description' => $this->description,
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
        */
        $user = $request->user();
        $author = $this->resource->author;
        return [
            'slug' => $this->resource->slug,
            'title' => $this->title,
            'description' => $this->description,
            'tagList' => $this->title,
            'createdAt' => $this->created_at,
            'updatedAt' => $this->updated_at,
            //'favorited' => $this->author,
            //'favoritesCount' => $this->favorites_count,
            'author' => new AuthorResource( $author ),
        ];
    }
}
