<?php

namespace App\Http\Controllers\Comment;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Article\Domain\Repositories\ArticleRepositoryInterface;
use Article\Domain\ValueObjects\ArticleSlug;
use User\Domain\ValueObjects\UserId;
use Article\Application\DeleteCommentUseCase;
use Article\Domain\DTO\DeleteCommentDTO;
use Article\Domain\ValueObjects\CommentId;

class DeleteCommentController extends Controller
{
    private $deleteCommentUseCase;

    public function __construct(ArticleRepositoryInterface $repository)
    {
        $this->deleteCommentUseCase = new DeleteCommentUseCase($repository);
    }

    public function __invoke(Request $request, string $slug, int $id)
    {
        $currentUserId = null;
        $user = $request->user();
        if ($user) {
            $currentUserId = new UserId($user->id);
        }

        $articleSlug = new ArticleSlug($slug);
        $commentId = new CommentId($id);

        $deleteCommentDTO = new DeleteCommentDTO(
            articleSlug: $articleSlug,
            userId: $currentUserId,
            commentId: $commentId
        );

        $comment = $this->deleteCommentUseCase->execute($deleteCommentDTO);

        if ($comment) {
            return response()->json([ 'delete_article' => $comment]);
        }

        return response()->json(['message' => 'Comment not found'], 404);
    }
}
