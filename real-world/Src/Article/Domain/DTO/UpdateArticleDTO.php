<?php

declare(strict_types=1);

namespace Article\Domain\DTO;

use Article\Domain\ValueObjects\ArticleTitle;
use Article\Domain\ValueObjects\ArticleDescription;
use Article\Domain\ValueObjects\ArticleBody;

final class UpdateArticleDTO
{
    private ?ArticleTitle $articleTitle;
    private ?ArticleDescription $articleDescription;
    private ?ArticleBody $articleBody;

    /**
     * @param ArticleBody $body
     */
    public function __construct(
        ?ArticleTitle $title = null,
        ?ArticleDescription $description = null,
        ?ArticleBody $body = null
    ) {
        $this->articleTitle = $title;
        $this->articleDescription = $description;
        $this->articleBody = $body;
    }

    public function title(): ?ArticleTitle
    {
        return $this->articleTitle;
    }

    public function description(): ?ArticleDescription
    {
        return $this->articleDescription;
    }

    public function body(): ?ArticleBody
    {
        return $this->articleBody;
    }

    public static function fromArray(array $data): UpdateArticleDTO
    {
        return new self(
            $data['title'] ? new ArticleTitle($data['title']) : null,
            $data['description'] ? new ArticleDescription($data['description']) : null,
            $data['body'] ? new ArticleBody($data['body']) : null,
        );
    }

    public function toArray(): array
    {
        $data = array();
        if ($this->articleTitle) {
            $data['title'] = $this->articleTitle->value();
        }

        if ($this->articleBody) {
            $data['body'] = $this->articleBody->value();
        }

        if ($this->articleDescription) {
            $data['description'] = $this->articleDescription->value();
        }
        return $data;
    }
}
