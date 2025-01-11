<?php

declare(strict_types=1);

namespace Article\Application;

use Article\Domain\Repositories\ArticleRepositoryInterface;
use Article\Domain\ValueObjects\ArticleSlug;
use User\Domain\ValueObjects\UserId;

final class GetArticleUseCase
{
	private $repository;

    public function __construct(ArticleRepositoryInterface $repository)
	{
		$this->repository = $repository;
    }

	public function execute(ArticleSlug $articleSlug, ?UserId $userId=null)
    {
		return $this->repository->findBySlug($articleSlug, $userId);
	}
}
