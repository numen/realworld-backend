<?php

namespace User\Domain\Repositories;

use User\Domain\Profile;
use User\Domain\User;
use User\Domain\ValueObjects\UserId;
use User\Domain\ValueObjects\UserName;

interface UserRepositoryInterface
{
    public function save(User $user);

    public function findById(UserId $id): ?User;

    public function findByUsername(UserName $name): ?User;

    public function getCurrentUser(): ?User;

    public function updateCurrentUser(UserId $user_id, array $data): ?User;

    public function update(UserId $user_id, User $user): void;

    public function delete(UserId $id): void;

    public function findProfile(UserName $followingName, UserId $currentUserId): ?Profile;

    public function toggleFollow(UserId $followerId, UserName $followingName, bool $follow): ?User;
}
