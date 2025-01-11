<?php

namespace App\Http\Resources\Article;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class ArticleCollection extends ResourceCollection
{
    public $collects = ArticleResource::class;
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'articles' => $this->collection,
            'articlesCount' => $this->collection->count(),
/*
            'total_members' => $this->collection->sum(function ($team) {
                return $team->users->count();
            }),
            */
        ];
    }
}
