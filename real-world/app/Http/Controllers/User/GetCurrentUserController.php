<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use Illuminate\Http\Request;

use User\Application\GetCurrentUserUseCase;
use User\Domain\Repositories\UserRepositoryInterface;

class GetCurrentUserController extends Controller
{
    private $getCurrentUserUseCase;

    public function __construct(UserRepositoryInterface $repository)
    {
        $this->getCurrentUserUseCase = new GetCurrentUserUseCase($repository);
    }

    public function __invoke(Request $request) {
        $user = new UserResource( $this->getCurrentUserUseCase->__invoke() );

        return response($user, 200);
    }
}
