<?php

declare(strict_types=1);

namespace User\Domain;

use User\Domain\ValueObjects\UserName;
use User\Domain\ValueObjects\UserEmail;
use User\Domain\ValueObjects\UserPassword;
use User\Domain\ValueObjects\UserRememberToken;
use User\Domain\ValueObjects\UserBio;
use User\Domain\ValueObjects\UserImage;

final class User
{
	private $username;
	private $email;
	private $password;
    private $remember_token;
    private ?UserBio $bio;
    private ?UserImage $image;

    /**
     * User constructor.
     *
     * @param UserName $UserName The username of the user.
     * @param UserEmail $UserEmail The email address of the user.
     * @param UserPassword $UserPassword The password of the user.
     * @param UserToken $UserToken The authentication token for the user.
     * @param UserBio|null $UserBio A brief biography of the user (can be null).
     * @param UserImage|null $UserImage The URL or path to the user's profile image (can be null).
     */
	public function __construct(
		UserName $username,
		UserEmail $email,
        UserPassword $password,
        UserRememberToken $remember_token,
        ?UserBio $bio = null,
        ?UserImage $image =null,
	)
	{
		$this->username = $username;
		$this->email = $email;
        $this->password = $password;
        $this->remember_token = $remember_token;
        $this->bio = $bio;
        $this->image = $image;
	}

	public function username(): UserName
	{
		return $this->username;
	}

	public function email(): UserEmail
	{
		return $this->email;
	}

	public function password(): UserPassword
	{
		return $this->password;
	}

    public function rememberToken(): UserRememberToken
    {
        return $this->remember_token;
    }

    public function bio(): ?UserBio
	{
		return $this->bio;
    }

	public function image(): ?UserImage
	{
		return $this->image;
	}
}
