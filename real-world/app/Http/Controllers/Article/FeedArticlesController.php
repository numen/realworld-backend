<?php

namespace App\Http\Controllers\Article;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\GetArticlesRequest;
use Article\Application\DTO\FilterArticlesDTO;
use Article\Application\FeedArticlesUseCase;
use Article\Domain\Repositories\ArticleRepositoryInterface;
use Illuminate\Support\Facades\Auth;
use User\Domain\ValueObjects\UserId;

class FeedArticlesController extends Controller
{
    private $feedArticlesUseCase;

    public function __construct(ArticleRepositoryInterface $repository)
    {
        $this->feedArticlesUseCase = new FeedArticlesUseCase($repository);
    }

    public function __invoke(GetArticlesRequest $request)
    {
        $filterArticlesDTO = FilterArticlesDTO::fromArray( $request->validated() );

        $currentUserId = null;
        $id = Auth::id();
        if ($id) {
            $currentUserId = new UserId($id);
        }

        $articlesArray = $this->feedArticlesUseCase->execute($filterArticlesDTO, $currentUserId);

        if ($articlesArray) {
            return response()->json([
                'articles' => $articlesArray,
                'articlesCount' => count($articlesArray)
            ]);
        }

        return response()->json(['message' => 'Articles not found'], 404);
    }

}
