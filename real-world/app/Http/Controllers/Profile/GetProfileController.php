<?php

namespace App\Http\Controllers\Profile;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\ProfileResource;
use User\Application\GetUserProfileUseCase;
use User\Domain\Repositories\UserRepositoryInterface;
use User\Domain\ValueObjects\UserId;
use User\Domain\ValueObjects\UserName;

class GetProfileController extends Controller
{
    private $getUserProfileUseCase;

    public function __construct(UserRepositoryInterface $repository)
    {
        $this->getUserProfileUseCase = new GetUserProfileUseCase($repository);
    }

    public function __invoke(Request $request, $username)
    {
        $currentUserId = null;
        $user = $request->user();
        if ($user) {
            $currentUserId = new UserId($user->id);
        }
        $followingName = new UserName($username);

        $profile = $this->getUserProfileUseCase->execute($followingName, $currentUserId);

        if ($profile) {
            return response(new ProfileResource($profile), 200);
        }

        return response()->json(['message' => 'Profile not found'], 404);
    }
}
