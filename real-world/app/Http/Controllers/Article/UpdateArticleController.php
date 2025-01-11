<?php

namespace App\Http\Controllers\Article;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Article\Domain\Repositories\ArticleRepositoryInterface;
use Article\Application\UpdateArticleUseCase;
use App\Http\Requests\Article\UpdateArticleRequest;
use Article\Domain\DTO\UpdateArticleDTO;
use Article\Domain\ValueObjects\ArticleSlug;
use User\Domain\ValueObjects\UserId;

class UpdateArticleController extends Controller
{
    private $updateArticleUseCase;

    public function __construct(ArticleRepositoryInterface $repository)
    {
        $this->updateArticleUseCase = new UpdateArticleUseCase($repository);
    }

    public function __invoke(UpdateArticleRequest $updateArticleRequest, string $slug)
    {
        $data = $updateArticleRequest->validated();

        $userModel = $updateArticleRequest->user();

        $currentUserId = new UserId( $userModel->getKey() );

        $newArticleDTO = UpdateArticleDTO::fromArray($data);
        $articleSlug = new ArticleSlug($slug);

        $article = $this->updateArticleUseCase->execute($articleSlug, $newArticleDTO, $currentUserId);

        if ($article) {
            return response()->json([ 'article' => $article]);
        }

        return response()->json(['message' => 'Article not found'], 404);
    }
}
