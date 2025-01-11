<?php

declare(strict_types=1);

namespace User\Application;

use User\Domain\Repositories\UserRepositoryInterface;
use User\Domain\User;

final class GetCurrentUserUseCase
{
    private $repository;

    public function __construct(UserRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function __invoke(): ?User
    {
        $user = $this->repository->getCurrentUser();

        return $user;
    }
}
