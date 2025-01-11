<?php

declare(strict_types=1);

namespace User\Application;

use User\Domain\Profile;
use User\Domain\Repositories\UserRepositoryInterface;
use User\Domain\ValueObjects\UserId;
use User\Domain\ValueObjects\UserName;
use Illuminate\Support\Facades\Log;

final class ToggleFollowUserUseCase
{
    private $repository;

    public function __construct(UserRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function execute(
        UserId $followerId,
        UserName $followingName,
        bool $follow = false ): ?Profile
    {
        $user = $this->repository->toggleFollow($followerId, $followingName, $follow);

        return Profile::fromUser($user, $follow);
    }
}
