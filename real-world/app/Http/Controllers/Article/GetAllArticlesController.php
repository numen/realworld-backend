<?php

namespace App\Http\Controllers\Article;

use App\Http\Controllers\Controller;
use App\Http\Requests\GetArticlesRequest;
use Article\Application\DTO\FilterArticlesDTO;
use Article\Application\GetAllArticlesUseCase;
use Article\Domain\Repositories\ArticleRepositoryInterface;
use Illuminate\Support\Facades\Auth;
use User\Domain\ValueObjects\UserId;

class GetAllArticlesController extends Controller
{
    private $getAllArticlesUseCase;

    public function __construct(ArticleRepositoryInterface $repository)
    {
        $this->getAllArticlesUseCase = new GetAllArticlesUseCase($repository);
    }

    public function __invoke(GetArticlesRequest $request)
    {
        $filterArticlesDTO = FilterArticlesDTO::fromArray( $request->validated() );

        $currentUserId = null;
        $id = Auth::id();
        if ($id) {
            $currentUserId = new UserId($id);
        }

        $articlesArray = $this->getAllArticlesUseCase->execute($filterArticlesDTO, $currentUserId);

        if ($articlesArray) {
            return response()->json([
                'articles' => $articlesArray,
                'articlesCount' => count($articlesArray)
            ]);
        }

        return response()->json(['message' => 'Articles not found'], 404);
    }

}
