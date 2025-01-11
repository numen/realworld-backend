<?php

declare(strict_types=1);

namespace User\Domain;

use JsonSerializable;
use User\Domain\ValueObjects\UserName;
use User\Domain\ValueObjects\UserBio;
use User\Domain\ValueObjects\UserImage;

final class Profile implements JsonSerializable
{
	private $username;
    private ?UserBio $bio;
    private ?UserImage $image;
    private bool $following;

	public function __construct(
		UserName $username,
        ?UserBio $bio = null,
        ?UserImage $image =null,
        bool $following = false,
	)
	{
		$this->username = $username;
        $this->bio = $bio;
        $this->image = $image;
        $this->following = $following;
    }

    public static function fromUser(User $user, bool $following = false): Profile
    {
        return new self(
            $user->username(),
            $user->bio(),
            $user->image(),
            $following
        );
    }

	public function username(): UserName
	{
		return $this->username;
	}

	public function following(): bool
	{
		return $this->following;
	}

    public function bio(): ?UserBio
	{
		return $this->bio;
    }

	public function image(): ?UserImage
	{
		return $this->image;
	}

    public function jsonSerialize():Array
    {
        return [
            'username' => $this->username->value(),
            'bio' => $this->bio? $this->bio->value(): null,
            'image' => $this->image? $this->image->value(): null,
            'following' => $this->following,
        ];
    }
}
