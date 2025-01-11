<?php

namespace App\Http\Controllers\Comment;

use App\Http\Controllers\Controller;
use Article\Application\GetAllCommentsUseCase;
use Illuminate\Http\Request;
use Article\Domain\Repositories\ArticleRepositoryInterface;
use Article\Domain\ValueObjects\ArticleSlug;
use User\Domain\ValueObjects\UserId;
use Illuminate\Support\Facades\Log;

class GetAllCommentsController extends Controller
{
    private $getAllCommentsUseCase;

    public function __construct(ArticleRepositoryInterface $repository)
    {
        $this->getAllCommentsUseCase = new GetAllCommentsUseCase($repository);
    }

    public function __invoke(Request $request, string $slug)
    {

        $currentUserId = null;
        $user = $request->user();
        if ($user) {
            $currentUserId = new UserId($user->id);
        }
        $articleSlug = new ArticleSlug($slug);

        $commentsArray = $this->getAllCommentsUseCase->execute($articleSlug, $currentUserId);

        if ($commentsArray) {
            return response()->json([
                'comments' => $commentsArray,
                'commentsCount' => count($commentsArray)
            ]);
        }

        return response()->json(['message' => 'Comments not found'], 404);
    }
}
