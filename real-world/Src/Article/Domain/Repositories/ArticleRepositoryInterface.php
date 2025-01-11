<?php

namespace Article\Domain\Repositories;

use Article\Domain\Article;
use Article\Domain\ArticleFilter;
use Article\Domain\ArticleSummary;
use Article\Domain\DTO\NewArticleDTO;
use Article\Domain\DTO\UpdateArticleDTO;
use Article\Domain\ValueObjects\ArticleId;
use Article\Domain\ValueObjects\ArticleSlug;
use User\Domain\ValueObjects\UserId;
use Article\Domain\Comment;
use Article\Domain\DTO\NewCommentDTO;
use Comment\Domain\ValueObjects\CommentId;
use Article\Domain\DTO\DeleteCommentDTO;

interface ArticleRepositoryInterface
{
    public function update(ArticleSlug $articleSlug, UpdateArticleDTO $updateArticleDTO, UserId $currentUserId): ?Article;

    public function delete(ArticleSlug $slug, ?UserId $userId=null): ?Article;

    public function save(NewArticleDTO $newArticleDTO): ?Article;

    public function findById(ArticleId $id): ?Article;

    public function findBySlug(ArticleSlug $slug, ?UserId $userId=null): ?Article;

    /**
     * @return array<ArticleSummary>
     */
    public function all(ArticleFilter $articleFilter, ?UserId $userId=null): array;

    /**
     * @return array<ArticleSummary>
     */
    public function feed(ArticleFilter $articleFilter, ?UserId $userId=null): array;

    public function toggleFavorite(UserId $userId, ArticleSlug $articleSlug, bool $favorited = false): ?Article;

    public function saveComment(NewCommentDTO $newCommentDTO): ?Comment;

    /**
     * @return array<Comment>
     */
    public function allArticleComments(ArticleSlug $slug, ?UserId $userId=null): array;

    public function deleteComment(DeleteCommentDTO $deleteCommentDTO): ?Comment;
}
