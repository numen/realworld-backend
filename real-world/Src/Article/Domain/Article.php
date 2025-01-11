<?php

declare(strict_types=1);

namespace Article\Domain;

use User\Domain\Profile;

use Article\Domain\ValueObjects\ArticleBody;
use Article\Domain\ValueObjects\ArticleCreated;
use Article\Domain\ValueObjects\ArticleDescription;
use Article\Domain\ValueObjects\ArticleSlug;
use Article\Domain\ValueObjects\ArticleTitle;
use Article\Domain\ValueObjects\ArticleUpdated;
use JsonSerializable;

final class Article implements JsonSerializable
{
	private $title;
	private $description;
	private $body;
	private $slug;
	private $createdAt;
	private $updatedAt;
    private $author;
    private bool $favorited;
    private int $favoritesCount;
    private array $tagList;

	public function __construct(
		ArticleTitle $title,
		ArticleDescription $description,
		ArticleBody $body,
		ArticleSlug $slug,
		ArticleCreated $createdAt,
		ArticleUpdated $updatedAt,
        bool $favorited = false,
        int $favoritesCount = 0,
        Profile $author,
        array $tagList = []
	)
	{
		$this->title = $title;
		$this->description = $description;
		$this->body = $body;
		$this->slug = $slug;
		$this->createdAt = $createdAt;
		$this->updatedAt = $updatedAt;
        $this->favorited = $favorited;
        $this->favoritesCount = $favoritesCount;
        $this->author = $author;
        $this->tagList = $tagList;
    }

	public function title(): ArticleTitle
	{
		return $this->title;
	}

	public function description(): ArticleDescription
	{
		return $this->description;
	}

	public function body(): ArticleBody
	{
		return $this->body;
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

    public function jsonSerialize():  array
    {
        return [
            'slug' => $this->slug->value(),
            'title' => $this->title->value(),
            'description' => $this->description->value(),
            'body' => $this->body->value(),
            'tagList' => $this->tagList,
            'createdAt' => $this->createdAt->serialize(),
            'updatedAt' => $this->updatedAt->serialize(),
            'favorited' => $this->favorited,
            'favoritesCount' => $this->favoritesCount,
            'author' => $this->author,
        ];

    }

    public function tagList(): array {
        return $this->tagList;
    }

}
