<?php

namespace App\Http\Controllers\Profile;

use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Http\Resources\ProfileResource;

use User\Application\ToggleFollowUserUseCase;
use User\Domain\Repositories\UserRepositoryInterface;
use User\Domain\ValueObjects\UserId;
use User\Domain\ValueObjects\UserName;
use Illuminate\Support\Facades\Log;

class FollowProfileController extends Controller
{
    private $toggleFollowUserUseCase;

    public function __construct(UserRepositoryInterface $repository)
    {
        $this->toggleFollowUserUseCase = new ToggleFollowUserUseCase($repository);
    }

    private function togglefollowProfile(string $username, bool $follow = false)
    {
        $currentUserId = new UserId( Auth::id() );

        $followingName = new UserName($username);

        $profile = $this->toggleFollowUserUseCase->execute($currentUserId, $followingName, $follow);

        if ($profile) {
            return response(new ProfileResource($profile), 200);
        }

        return response()->json(['message' => 'Profile not found'], 404);
    }


    public function followProfile(string $username)
    {
        return $this->togglefollowProfile($username, true);
    }

    public function unfollowProfile(string $username)
    {
        return $this->togglefollowProfile($username, false);
    }

}
