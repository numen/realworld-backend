<?php
declare(strict_types=1);

namespace Article\Domain;

final class ArticleFilter
{
	private ?string $tag;
	private ?string $author;
	private ?string $favorited;
	private int $limit;
	private int $offset;

	public function __construct(
		?string $tag,
		?string $author,
		?string $favorited,
		int $limit,
		int $offset
	)
	{
		$this->tag = $tag;
		$this->author = $author;
		$this->favorited = $favorited;
		$this->limit = $limit;
		$this->offset = $offset;
	}

	public function tag(): ?string
	{
		return $this->tag;
	}

	public function author(): ?string
	{
		return $this->author;
	}

	public function favorited(): ?string
	{
		return $this->favorited;
	}

	public function limit(): int
	{
		return $this->limit;
	}

	public function offset(): int
	{
		return $this->offset;
	}
}
