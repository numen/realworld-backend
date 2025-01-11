<?php

declare(strict_types=1);

namespace Article\Domain\DTO;

use Article\Domain\ValueObjects\ArticleSlug;
use Article\Domain\ValueObjects\CommentId;
use User\Domain\ValueObjects\UserId;

final class DeleteCommentDTO
{
    private $articleSlug;
    private $commentId;
    private $userId;

    /**
     * @param CommentId $id
     */
    public function __construct(
        ArticleSlug $articleSlug,
        UserId $userId,
        CommentId $commentId
    ) {
        $this->articleSlug = $articleSlug;
        $this->commentId = $commentId;
        $this->userId = $userId;
    }

    public function slug(): ArticleSlug
    {
        return $this->articleSlug;
    }

    public function id(): CommentId
    {
        return $this->commentId;
    }

    public function userId(): UserId
    {
        return $this->userId;
    }

}
