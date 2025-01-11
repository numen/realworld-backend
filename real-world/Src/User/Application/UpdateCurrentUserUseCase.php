<?php

namespace User\Application;

use User\Application\DTO\UpdateUserDTO;
use User\Domain\Repositories\UserRepositoryInterface;
use User\Domain\User;
use User\Domain\ValueObjects\UserId;

class UpdateCurrentUserUseCase
{
    private UserRepositoryInterface $userRepository;

    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function execute(UserId $id, UpdateUserDTO $dto): User
    {
        //$user = $this->userRepository->findById( new UserId($id) );

        return $this->userRepository->updateCurrentUser($id, $dto->toArray());
    }
}
