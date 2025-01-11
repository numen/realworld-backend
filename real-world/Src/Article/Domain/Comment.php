<?php
declare(strict_types=1);

namespace Article\Domain;

use JsonSerializable;

use Article\Domain\ValueObjects\CommentId;
use Article\Domain\ValueObjects\CommentBody;
use Article\Domain\ValueObjects\CommentCreated;
use Article\Domain\ValueObjects\CommentUpdated;
use User\Domain\Profile;

final class Comment implements JsonSerializable
{
	private $id;
	private $createdAt;
	private $updatedAt;
	private $body;
	private $author;

	public function __construct(
		CommentId $id,
		CommentCreated $createdAt,
		CommentUpdated $updatedAt,
		CommentBody $body,
		Profile $author
	)
	{
		$this->id = $id;
		$this->createdAt = $createdAt;
		$this->updatedAt = $updatedAt;
		$this->body = $body;
		$this->author = $author;
	}

	public function id(): CommentId
	{
		return $this->id;
	}

	public function createdAt(): CommentCreated
	{
		return $this->createdAt;
	}

	public function updatedAt(): CommentUpdated
	{
		return $this->updatedAt;
	}

	public function body(): CommentBody
	{
		return $this->body;
	}

	public function author(): Profile
	{
		return $this->author;
	}

    public function jsonSerialize():  array
    {
        return [
            'id' => $this->id->value(),
            'createdAt' => $this->createdAt->serialize(),
            'updatedAt' => $this->updatedAt->serialize(),
            'body' => $this->body->value(),
            'author' => $this->author,
        ];
    }
}
