<?php

declare(strict_types=1);

namespace Article\Domain\DTO;

use Article\Domain\ValueObjects\ArticleSlug;
use Article\Domain\ValueObjects\CommentBody;
use User\Domain\ValueObjects\UserId;

final class NewCommentDTO
{
    private $articleSlug;
    private $commentBody;
    private $authorId;

    /**
     * @param CommentBody $body
     */
    public function __construct(
        ArticleSlug $articleSlug,
        UserId $authorId,
        CommentBody $body
    ) {
        $this->articleSlug = $articleSlug;
        $this->commentBody = $body;
        $this->authorId = $authorId;
    }

    public function slug(): ArticleSlug
    {
        return $this->articleSlug;
    }

    public function body(): CommentBody
    {
        return $this->commentBody;
    }

    public function authorId(): UserId
    {
        return $this->authorId;
    }

}
