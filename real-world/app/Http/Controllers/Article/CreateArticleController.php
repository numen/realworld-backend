<?php

namespace App\Http\Controllers\Article;

use App\Http\Controllers\Controller;
use App\Http\Requests\Article\NewArticleRequest;
use Article\Application\CreateArticleUseCase;
use Article\Domain\DTO\NewArticleDTO;
use Article\Domain\Repositories\ArticleRepositoryInterface;

class CreateArticleController extends Controller
{
    private $createArticleUseCase;

    public function __construct(ArticleRepositoryInterface $repository)
    {
        $this->createArticleUseCase = new CreateArticleUseCase($repository);
    }

    public function __invoke(NewArticleRequest $newArticleRequest)
    {
        $data = $newArticleRequest->validated();

        $userModel = $newArticleRequest->user();

        $data['author_id'] = $userModel->getKey();

        $newArticleDTO = NewArticleDTO::fromArray($data);

        $article = $this->createArticleUseCase->execute($newArticleDTO);

        if ($article) {
            return response()->json([ 'article' => $article]);
        }

        return response()->json(['message' => 'Article not found'], 404);
    }
}
