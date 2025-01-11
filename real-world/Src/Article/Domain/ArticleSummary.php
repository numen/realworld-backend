<?php

declare(strict_types=1);

namespace Article\Domain;

use User\Domain\Profile;

use Article\Domain\ValueObjects\ArticleCreated;
use Article\Domain\ValueObjects\ArticleDescription;
use Article\Domain\ValueObjects\ArticleSlug;
use Article\Domain\ValueObjects\ArticleTitle;
use Article\Domain\ValueObjects\ArticleUpdated;
use JsonSerializable;

final class ArticleSummary implements JsonSerializable
{
	private $title;
	private $description;
	private $slug;
	private $createdAt;
	private $updatedAt;
    private $author;
    private $favorited;
    private $favoritesCount;

	public function __construct(
		ArticleTitle $title,
		ArticleSlug $slug,
		ArticleDescription $description,
		ArticleCreated $createdAt,
        ArticleUpdated $updatedAt,
        bool $favorited = false,
        int $favoritesCount = 0,
		Profile $author
	)
	{
		$this->title = $title;
		$this->description = $description;
		$this->slug = $slug;
		$this->createdAt = $createdAt;
		$this->updatedAt = $updatedAt;
        $this->author = $author;
        $this->favorited = $favorited;
        $this->favoritesCount = $favoritesCount;
	}
	public function title(): ArticleTitle
	{
		return $this->title;
	}

	public function description(): ArticleDescription
	{
		return $this->description;
	}

	public function slug(): ArticleSlug
	{
		return $this->slug;
	}

	public function createdAt(): ArticleCreated
	{
		return $this->createdAt;
	}

	public function updatedAt(): ArticleUpdated
	{
		return $this->updatedAt;
	}

	public function author(): Profile
	{
		return $this->author;
    }

    public function favorited(): bool {
        return $this->favorited;
    }

    public function favoritesCount(): int {
        return $this->favoritesCount;
    }

    public function jsonSerialize():Array
    {
        return [
            'slug' => $this->slug->value(),
            'title' => $this->title->value(),
            'description' => $this->description->value(),

            'tagList' => 'tags', // TODO tagList property pending

            'createdAt' => $this->createdAt->serialize(),
            'updatedAt' => $this->updatedAt->serialize(),
            'favorited' => $this->favorited,
            'favoritesCount' => $this->favoritesCount,
            'author' => $this->author,
        ];

    }
}
