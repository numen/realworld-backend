<?php

namespace App\Http\Controllers\Article;

use App\Http\Controllers\Controller;
use Article\Application\DeleteArticleUseCase;
use Illuminate\Http\Request;

use Article\Domain\Repositories\ArticleRepositoryInterface;
use Article\Domain\ValueObjects\ArticleSlug;
use User\Domain\ValueObjects\UserId;

class DeleteArticleController extends Controller
{
    private $deleteArticleUseCase;

    public function __construct(ArticleRepositoryInterface $repository)
    {
        $this->deleteArticleUseCase = new DeleteArticleUseCase($repository);
    }

    public function __invoke(Request $request, string $slug)
    {
        $currentUserId = null;
        $user = $request->user();
        if ($user) {
            $currentUserId = new UserId($user->id);
        }

        $articleSlug = new ArticleSlug($slug);

        $article = $this->deleteArticleUseCase->execute($articleSlug, $currentUserId);

        if ($article) {
            return response()->json([ 'delete_article' => $article]);
        }

        return response()->json(['message' => 'Article not found'], 404);
    }
    //
}
