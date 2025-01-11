<?php

declare(strict_types=1);

namespace User\Application;

use User\Domain\Repositories\UserRepositoryInterface;
use User\Domain\User;
use User\Domain\ValueObjects\UserId;

final class GetUserUseCase
{
    private $repository;

    public function __construct(UserRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function __invoke(string $userId): ?User
    {
        $id = new UserId($userId);

        $user = $this->repository->findById($id);

        return $user;
    }
}
