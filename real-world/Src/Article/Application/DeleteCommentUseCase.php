<?php

declare(strict_types=1);

namespace Article\Application;

use Article\Domain\DTO\DeleteCommentDTO;
use Article\Domain\Repositories\ArticleRepositoryInterface;

final class DeleteCommentUseCase
{
	private $repository;

    public function __construct(ArticleRepositoryInterface $repository)
	{
		$this->repository = $repository;
    }

	public function execute(DeleteCommentDTO $deleteCommentDTO)
    {
		return $this->repository->deleteComment($deleteCommentDTO);
	}
}
