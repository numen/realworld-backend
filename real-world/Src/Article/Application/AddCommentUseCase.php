<?php

declare(strict_types=1);

namespace Article\Application;

use Article\Domain\DTO\NewCommentDTO;
use Article\Domain\Repositories\ArticleRepositoryInterface;

final class AddCommentUseCase
{
	private $repository;

    public function __construct(ArticleRepositoryInterface $repository)
	{
		$this->repository = $repository;
    }

	public function execute(NewCommentDTO $newCommentDTO)
    {
		return $this->repository->saveComment($newCommentDTO);
	}
}
