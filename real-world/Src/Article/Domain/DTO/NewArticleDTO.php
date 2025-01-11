<?php

declare(strict_types=1);

namespace Article\Domain\DTO;

use Article\Domain\ValueObjects\ArticleTitle;
use Article\Domain\ValueObjects\ArticleDescription;
use Article\Domain\ValueObjects\ArticleBody;
use User\Domain\ValueObjects\UserId;

final class NewArticleDTO
{
    private $articleTitle;
    private $articleDescription;
    private $articleBody;
    private $tagList;
    private $authorId;

    /**
     * @param array<string> $tagList
     */
    public function __construct(
        ArticleTitle $title,
        ArticleDescription $description,
        ArticleBody $body,
        UserId $authorId,
        array $tagList = []
    ) {
        $this->articleTitle = $title;
        $this->articleDescription = $description;
        $this->articleBody = $body;
        $this->authorId = $authorId;
        $this->tagList = $tagList;
    }

    public function title(): ArticleTitle
    {
        return $this->articleTitle;
    }

    public function description(): ArticleDescription
    {
        return $this->articleDescription;
    }

    public function body(): ArticleBody
    {
        return $this->articleBody;
    }

    public function tagList(): array
    {
        return $this->tagList;
    }

    public function authorId(): UserId
    {
        return $this->authorId;
    }

    public static function fromArray(array $data): NewArticleDTO
    {
        return new self(
            new ArticleTitle( $data['title'] ),
            new ArticleDescription( $data['description'] ),
            new ArticleBody( $data['body'] ),
            new UserId( $data['author_id'] ),
            $data['tagList']?? [],
        );
    }
}
