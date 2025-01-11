<?php
namespace Article\Application;

use Article\Domain\Article;
use Article\Domain\Repositories\ArticleRepositoryInterface;
use Article\Domain\ValueObjects\ArticleSlug;
use User\Domain\ValueObjects\UserId;

final class ToggleFavoriteArticleUseCase
{
    private $repository;

    public function __construct(ArticleRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function execute(
        UserId $userId,
        ArticleSlug $articleSlug,
        bool $favorited = false ): ?Article
    {
        $article = $this->repository->toggleFavorite($userId, $articleSlug, $favorited);

        return $article;
    }
}

