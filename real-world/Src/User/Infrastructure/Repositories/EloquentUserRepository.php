<?php

declare(strict_types=1);

namespace User\Infrastructure\Repositories;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\User as EloquentUserModel;
use User\Domain\Profile;
use User\Domain\Repositories\UserRepositoryInterface;
use User\Domain\User;
use User\Domain\ValueObjects\UserEmail;
use User\Domain\ValueObjects\UserId;
use User\Domain\ValueObjects\UserName;
use User\Domain\ValueObjects\UserPassword;
use User\Domain\ValueObjects\UserRememberToken;
use User\Domain\ValueObjects\UserBio;
use User\Domain\ValueObjects\UserImage;

final class EloquentUserRepository implements UserRepositoryInterface
{
    private $eloquentUserModel;

    public function __construct()
    {
        $this->eloquentUserModel = new EloquentUserModel;
    }

    public function findById(UserId $id): ?User
    {
        $user = $this->eloquentUserModel->findOrFail($id->value());

        // Return Domain User model
        return new User(
            new UserName($user->name),
            new UserEmail($user->email),
            //new UserEmailVerifiedDate($user->email_verified_at),
            new UserPassword($user->password),
            new UserRememberToken($user->remember_token)
        );
    }

    public function getCurrentUser(): ?User
    {
        $user = Auth::user();
        //$profile = $user->profile;

        // Return Domain User model
        return new User(
            new UserName($user->username),
            new UserEmail($user->email),
            //new UserEmailVerifiedDate($user->email_verified_at),
            new UserPassword($user->password),
            new UserRememberToken($user->getRememberToken()),
            ($user->bio) ? new UserBio($user->bio) : null,
            ($user->image) ? new UserImage($user->image) : null,
        );
    }

    public function updateCurrentUser(UserId $id, array $data): ?User
    {
        $user = $this->eloquentUserModel::findOrFail($id->value());

        $user->update($data);

        // Return Domain User model
        return new User(
            new UserName($user->username),
            new UserEmail($user->email),
            new UserPassword($user->password),
            new UserRememberToken($user->getRememberToken()),
            ($user->bio) ? new UserBio($user->bio) : null,
            ($user->image) ? new UserImage($user->image) : null,
        );
    }

    public function findByCriteria(UserName $name, UserEmail $email): ?User
    {
        $user = $this->eloquentUserModel
            ->where('name', $name->value())
            ->where('email', $email->value())
            ->firstOrFail();

        // Return Domain User model
        return new User(
            new UserName($user->name),
            new UserEmail($user->email),
            //new UserEmailVerifiedDate($user->email_verified_at),
            new UserPassword($user->password),
            new UserRememberToken($user->remember_token)
        );
    }

    public function findByUsername(UserName $name): ?User
    {
        $user = $this->eloquentUserModel
            ->where('username', $name->value())
            ->firstOrFail();

        // Return Domain User model
        return new User(
            new UserName($user->username),
            new UserEmail($user->email),
            new UserPassword($user->password),
            new UserRememberToken($user->getRememberToken()),
            ($user->bio) ? new UserBio($user->bio) : null,
            ($user->image) ? new UserImage($user->image) : null
        );
    }

    public function getFollowingWithFollowStatus($followingId, $currentUserId)
    {
        $following = DB::table('users')
            ->leftJoin('followers', function ($join) use ($currentUserId) {
                $join->on('followers.following_id', '=', 'users.id')
                    ->where('followers.follower_id', '=', $currentUserId);
            })
            ->select('users.*', DB::raw('IF(followers.follower_id IS NOT NULL, 1, 0) as is_following'))
            ->where('users.id', $followingId)
            ->first();

        return $following;
    }


    public function save(User $user): void
    {
        $newUser = $this->eloquentUserModel;

        $data = [
            'username'              => $user->username()->value(),
            'email'             => $user->email()->value(),
            // 'email_verified_at' => $user->emailVerifiedDate()->value(),
            'password'          => $user->password()->value(),
            'remember_token'    => $user->rememberToken()->value(),
        ];

        $newUser->create($data);
    }

    public function update(UserId $id, User $user): void
    {
        $userToUpdate = $this->eloquentUserModel;

        $data = [
            'username'  => $user->username()->value(),
            'email' => $user->email()->value(),
        ];

        $userToUpdate
            ->findOrFail($id->value())
            ->update($data);
    }

    public function delete(UserId $id): void
    {
        $this->eloquentUserModel
            ->findOrFail($id->value())
            ->delete();
    }

    public function findProfile(UserName $followingName, UserId $currentUserId): ?Profile
    {
        $following = DB::table('users')
            ->leftJoin('followers', function ($join) use ($currentUserId) {
                $join->on('followers.following_id', '=', 'users.id')
                    ->where('followers.follower_id', '=', $currentUserId->value());
            })
            ->select('users.*', DB::raw('IF(followers.follower_id IS NOT NULL, 1, 0) as is_following'))
            ->where('users.username', $followingName->value())
            ->first();

        if ($following) {
            return new Profile(
                new UserName( $following->username ),
                ($following->bio) ? new UserBio( $following->bio ): null,
                ($following->image) ? new UserImage( $following->image): null,
                boolval( $following->is_following )
            );
        }
        return null;
    }

    public function toggleFollow(UserId $followerId, UserName $followingName, bool $follow): ?User {
        $followingUser = $this->eloquentUserModel
            ->where('username', $followingName->value())
            ->firstOrFail();

        $followerUser = $this->eloquentUserModel
            ->findOrFail( $followerId->value() );

        if($follow) {
            $followerUser->following()->attach($followingUser);
        }
        else { // unfollow
            $followerUser->following()->detach($followingUser);
        }

        return new User(
            new UserName($followingUser->username),
            new UserEmail($followingUser->email),
            new UserPassword($followingUser->password),
            new UserRememberToken($followingUser->getRememberToken()),
            ($followingUser->bio) ? new UserBio( $followingUser->bio ): null,
            ($followingUser->image) ? new UserImage( $followingUser->image): null
        );

    }
}
