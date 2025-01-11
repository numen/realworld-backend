<?php

declare(strict_types=1);

namespace User\Domain\ValueObjects;

final class UserProfile
{
	private $name;
	private $bio;
	private $image;

	public function __construct(
		string $name,
		string $bio,
		string $image
	)
	{
		$this->name = $name;
		$this->bio = $bio;
		$this->image = $image;
	}

	public function name(): string
	{
		return $this->name;
	}

	public function bio(): string
	{
		return $this->bio;
	}

	public function image(): string
	{
		return $this->image;
	}
}

