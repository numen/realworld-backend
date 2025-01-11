<?php

declare(strict_types=1);

namespace Article\Application;

use Article\Application\DTO\FilterArticlesDTO;
use Article\Domain\ArticleFilter;
use Article\Domain\Repositories\ArticleRepositoryInterface;
use User\Domain\ValueObjects\UserId;

final class FeedArticlesUseCase
{
	private $repository;

    public function __construct(ArticleRepositoryInterface $repository)
	{
		$this->repository = $repository;
    }

	public function execute(FilterArticlesDTO $filterArticlesDTO, ?UserId $userId=null)
    {
        $articleFilter = new ArticleFilter(
            $filterArticlesDTO->tag(),
            $filterArticlesDTO->author(),
            $filterArticlesDTO->favorited(),
            $filterArticlesDTO->limit(),
            $filterArticlesDTO->offset()
        );

		return $this->repository->feed($articleFilter, $userId);
	}
}
