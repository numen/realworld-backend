<?php

namespace App\Http\Controllers\Comment;

use App\Http\Controllers\Controller;
use App\Http\Requests\Comment\NewCommentRequest;
use Article\Application\AddCommentUseCase;
use Article\Domain\DTO\NewCommentDTO;
use Illuminate\Http\Request;

use Article\Domain\Repositories\ArticleRepositoryInterface;
use Article\Domain\ValueObjects\ArticleSlug;
use Article\Domain\ValueObjects\CommentBody;
use User\Domain\ValueObjects\UserId;

use Illuminate\Support\Facades\Log;

class AddCommentController extends Controller
{
    private $addCommentUseCase;

    public function __construct(ArticleRepositoryInterface $repository)
    {
        $this->addCommentUseCase = new AddCommentUseCase($repository);
    }

    public function __invoke(Request $newCommentRequest, string $slug)
    {
        $newCommentRequest->validate([
            'body' => 'required|string',
        ]);

        $data = $newCommentRequest->only('body');

        Log::debug('An informational {articleId}',['articleId' => $data ]);

        $user = $newCommentRequest->user();
        $currentUserId = new UserId($user->id);

        $articleSlug = new ArticleSlug($slug);

        $commentBody = new CommentBody($data['body']);

        $newCommentDTO = new NewCommentDTO(
            articleSlug: $articleSlug,
            authorId: $currentUserId,
            body: $commentBody);

        $comment = $this->addCommentUseCase->execute($newCommentDTO);

        if ($comment) {
            return response()->json([ 'comment' => $comment]);
        }

        return response()->json(['message' => 'error save Comment'], 404);
    }
}
