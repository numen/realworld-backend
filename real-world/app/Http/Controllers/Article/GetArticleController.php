<?php

namespace App\Http\Controllers\Article;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Article\Domain\Repositories\ArticleRepositoryInterface;
use Article\Application\GetArticleUseCase;
use Article\Domain\ValueObjects\ArticleSlug;
use User\Domain\ValueObjects\UserId;

class GetArticleController extends Controller
{
    private $getArticleUseCase;

    public function __construct(ArticleRepositoryInterface $repository)
    {
        $this->getArticleUseCase = new GetArticleUseCase($repository);
    }

    public function __invoke(Request $request, string $slug)
    {
        $currentUserId = null;
        $user = $request->user();
        if ($user) {
            $currentUserId = new UserId($user->id);
        }

        $articleSlug = new ArticleSlug($slug);

        $article = $this->getArticleUseCase->execute($articleSlug, $currentUserId);

        if ($article) {
            return response()->json([ 'article' => $article]);
        }

        return response()->json(['message' => 'Article not found'], 404);
    }
}
