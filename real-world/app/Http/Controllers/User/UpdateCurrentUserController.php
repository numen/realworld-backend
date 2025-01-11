<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\PutCurrentUserRequest;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\UserResource;

use Illuminate\Http\Request;
use User\Application\DTO\UpdateUserDTO;
use User\Application\UpdateCurrentUserUseCase;
use User\Domain\ValueObjects\UserId;

class UpdateCurrentUserController extends Controller
{
    private $update_current_user_usecase;

    public function __construct(UpdateCurrentUserUseCase $update_current_user_usecase)
    {
        $this->update_current_user_usecase = $update_current_user_usecase;
    }

    public function __invoke(PutCurrentUserRequest $request) {
        $user = Auth::user();
        //$user->update("");

        $data = $request->validated();
        $updateUserDTO = new UpdateUserDTO($data);

        $user_id = new UserId( $user->id );

        $result = $this->update_current_user_usecase->execute($user_id, $updateUserDTO);

        $user = new UserResource( $result );

        return response($user, 200);
    }
}
