<?php

declare(strict_types=1);

namespace Article\Application;

use Article\Domain\DTO\UpdateArticleDTO;
use Article\Domain\Repositories\ArticleRepositoryInterface;
use Article\Domain\ValueObjects\ArticleSlug;
use User\Domain\ValueObjects\UserId;

final class UpdateArticleUseCase
{
	private $repository;

    public function __construct(ArticleRepositoryInterface $repository)
	{
		$this->repository = $repository;
    }

	public function execute(ArticleSlug $articleSlug, UpdateArticleDTO $updateArticleDTO, UserId $currentUserId)
    {
		return $this->repository->update($articleSlug, $updateArticleDTO, $currentUserId);
	}
}
