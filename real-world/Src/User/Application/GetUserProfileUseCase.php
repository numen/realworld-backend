<?php

declare(strict_types=1);

namespace User\Application;

use User\Domain\Profile;
use User\Domain\Repositories\UserRepositoryInterface;
use User\Domain\ValueObjects\UserId;
use User\Domain\ValueObjects\UserName;
use Illuminate\Support\Facades\Log;

final class GetUserProfileUseCase
{
    private $repository;

    public function __construct(UserRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function execute(UserName $followingName, ?UserId $currentUserId = null): ?Profile
    {
        $profile = null;

        if ($currentUserId) {
            //Log::debug('An informational {message2}',['message2' => $profile]);
            $profile = $this->repository->findProfile($followingName, $currentUserId);
        }
        else {
            $user = $this->repository->findByUsername($followingName);
            $profile = Profile::fromUser($user);
        }

        return $profile;
    }
}
