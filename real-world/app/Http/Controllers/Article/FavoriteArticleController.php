<?php

namespace App\Http\Controllers\Article;

use App\Http\Controllers\Controller;
use Article\Domain\Repositories\ArticleRepositoryInterface;
use Illuminate\Http\Request;

use Article\Application\ToggleFavoriteArticleUseCase;
use Article\Domain\ValueObjects\ArticleSlug;
use Illuminate\Support\Facades\Auth;
use User\Domain\ValueObjects\UserId;

class FavoriteArticleController extends Controller
{
    private $toggleFavoriteArticleUseCase;

    public function __construct(ArticleRepositoryInterface $repository)
    {
        $this->toggleFavoriteArticleUseCase = new ToggleFavoriteArticleUseCase($repository);
    }

    private function togglefavoriteArticle(string $slug, bool $favorite = false)
    {
        $currentUserId = new UserId( Auth::id() );

        $articleSlug = new ArticleSlug($slug);

        $article = $this->toggleFavoriteArticleUseCase->execute($currentUserId, $articleSlug, $favorite);

        if ($article) {
            return response( $article, 200);
        }

        return response()->json(['message' => 'article not found'], 404);
    }


    public function favoriteArticle(Request $request,string $slug)
    {
        return $this->togglefavoriteArticle($slug, true);
        //return response()->json(['message' => $slug], 200);
    }

    public function unfavoriteArticle(Request $request, string $slug)
    {
        return $this->togglefavoriteArticle($slug, false);
    }

}
