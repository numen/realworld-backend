<?php

declare(strict_types=1);

namespace Article\Application;

use Article\Domain\DTO\NewArticleDTO;
use Article\Domain\Repositories\ArticleRepositoryInterface;

final class CreateArticleUseCase
{
	private $repository;

    public function __construct(ArticleRepositoryInterface $repository)
	{
		$this->repository = $repository;
    }

	public function execute(NewArticleDTO $newArticleDTO)
    {
		return $this->repository->save($newArticleDTO);
	}
}
