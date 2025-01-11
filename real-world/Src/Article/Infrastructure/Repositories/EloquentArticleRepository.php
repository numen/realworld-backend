<?php

namespace Article\Infrastructure\Repositories;

use App\Models\Article as EloquentArticleModel;
use App\Models\User as EloquentUserModel;
use App\Models\Comment as EloquentCommentModel;

use Article\Domain\Article;
use Article\Domain\ArticleFilter;
use Article\Domain\ArticleSummary;
use Article\Domain\Repositories\ArticleRepositoryInterface;
use Article\Domain\ValueObjects\ArticleBody;
use Article\Domain\ValueObjects\ArticleCreated;
use Article\Domain\ValueObjects\ArticleDescription;
use Article\Domain\ValueObjects\ArticleId;
use Article\Domain\ValueObjects\ArticleSlug;
use Article\Domain\ValueObjects\ArticleTitle;
use Article\Domain\ValueObjects\ArticleUpdated;
use Article\Domain\DTO\NewArticleDTO;
use Article\Domain\DTO\UpdateArticleDTO;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use User\Domain\Profile as DomainProfile;
use User\Domain\ValueObjects\UserBio;
use User\Domain\ValueObjects\UserImage;
use User\Domain\ValueObjects\UserId;
use User\Domain\ValueObjects\UserName;

use Article\Domain\DTO\NewCommentDTO;
use Article\Domain\Comment;
use Article\Domain\DTO\DeleteCommentDTO;
use Article\Domain\ValueObjects\CommentBody;
use Article\Domain\ValueObjects\CommentCreated;
use Article\Domain\ValueObjects\CommentId;
use Article\Domain\ValueObjects\CommentUpdated;

use function Laravel\Prompts\select;

final class EloquentArticleRepository implements ArticleRepositoryInterface
{
    private $model;

    public function __construct()
    {
        $this->model = new EloquentArticleModel;
    }

    public function update(ArticleSlug $articleSlug, UpdateArticleDTO $updateArticleDTO, UserId $currentUserId): ?Article
    {
        $articleModel = EloquentArticleModel::whereSlug($articleSlug->value())
            ->firstOrFail();

        $dataArticle = $updateArticleDTO->toArray();

        if($dataArticle['title']) {
            $dataArticle['slug'] = Str::of( $dataArticle['title'] )->slug('-');
        }

        // $this->authorize('update', $article); // TODO Policy

        $articleModel->update( $dataArticle );

        $article = $this->articleModelToArticle(
            articleModel: $articleModel,
            currentUserId: $currentUserId);

        return $article;
    }

    public function save(NewArticleDTO $newArticleDTO): ?Article
    {
        $title = $newArticleDTO->title()->value();
        $slug = Str::of($title)->slug('-');

        $dataArticle = [
            'title'       => $title,
            'slug'        => $slug,
            'description' => $newArticleDTO->description()->value(),
            'body'        => $newArticleDTO->body()->value(),
            'user_id'     => $newArticleDTO->authorId()->value(),
        ];

        $tags = $newArticleDTO->tagList();

        $articleModel = $this->model->create($dataArticle);

        if (is_array($tags)) {
            $articleModel->attachTags($tags);
        }

        $article = $this->articleModelToArticle(
            articleModel: $articleModel,
            currentUserId: $newArticleDTO->authorId()
        );

        return $article;
    }

    public function findById(ArticleId $id): ?Article
    {
        return null;
    }

    public function findBySlug(ArticleSlug $articleSlug, ?UserId $currentUserId = null): ?Article
    {
        $articleModel = $this->model
            ->where('slug', $articleSlug->value())
            ->firstOrFail();

        $article = $this->articleModelToArticle($articleModel, $currentUserId);

        return $article;
    }

    public function all(ArticleFilter $articleFilter, ?UserId $currentUserId = null): array
    {
        $listArticles = EloquentArticleModel::listA(
            //($userId)? $userId->value(): null,
            $articleFilter->limit(),
            $articleFilter->offset()
        );

        if ($authorName = $articleFilter->author()) {
            $listArticles->ofAuthor($authorName);
        }

        if ($tagName = $articleFilter->tag()) {
            $listArticles->ofTag($tagName);
        }

        if ($username = $articleFilter->favorited()) {
            $listArticles->favoredByUser($username);
        }
        //Log::debug('An informational {message2}',['message2' => $a[0]->author ]);

        return $listArticles->get()->map(function ($articleModel) use($currentUserId) {

            return $this->articleModelToArticleSummary(
                articleModel: $articleModel,
                currentUserId: $currentUserId
            );

        })->toArray();
    }

    public function feed(ArticleFilter $articleFilter, ?UserId $currentUserId = null): array
    {
        $userModel = EloquentUserModel::findOrFail($currentUserId->value());

        $listArticles = EloquentArticleModel::listA(
            $articleFilter->limit(),
            $articleFilter->offset()
        )->followedAuthorsOf($userModel);

        if ($authorName = $articleFilter->author()) {
            $listArticles->ofAuthor($authorName);
        }

        if ($tagName = $articleFilter->tag()) {
            $listArticles->ofTag($tagName);
        }

        if ($username = $articleFilter->favorited()) {
            $listArticles->favoredByUser($username);
        }
        //Log::debug('An informational {message2}',['message2' => $a[0]->author ]);

        return $listArticles->get()->map(function ($articleModel) use ($currentUserId) {

            return $this->articleModelToArticleSummary(
                articleModel: $articleModel,
                currentUserId: $currentUserId
            );

        })->toArray();
    }

    public function toggleFavorite(UserId $userId, ArticleSlug $articleSlug, bool $favorited = false): ?Article
    {
        $articleModel = $this->model
            ->where('slug', $articleSlug->value())
            ->firstOrFail();

        $userModel = new EloquentUserModel();

        $user = $userModel->where('id', $userId->value())
            ->firstOrFail();

        if ($favorited) {
            $user->favoriteArticles()->syncWithoutDetaching($articleModel);
        } else { // unfavorite
            $user->favoriteArticles()->detach($articleModel);
        }

        $author = new DomainProfile(
            new UserName($user->username),
            ($user->bio) ? new UserBio($user->bio) : null,
            ($user->image) ? new UserImage($user->image) : null,
            boolval($user->is_following)
        );

        $favoritesCount = $articleModel->favoredUsers->count();

        $article = new Article(
            new ArticleTitle($articleModel->title),
            new ArticleDescription($articleModel->description),
            new ArticleBody($articleModel->body),
            new ArticleSlug($articleModel->slug),
            new ArticleCreated($articleModel->created_at->format('Y-m-d H:i:s')),
            new ArticleUpdated($articleModel->updated_at->format('Y-m-d H:i:s')),
            $articleModel->isFavoredByUser($user),
            $favoritesCount,
            $author
        );

        return $article;
    }

    public function delete(ArticleSlug $slug, ?UserId $userId = null): ?Article
    {
        $articleModel = $this->model
            ->where('slug', $slug->value())
        ->firstOrFail();

        $articleModel->delete();

        return $this->articleModelToArticle($articleModel);
    }

    public function saveComment(NewCommentDTO $newCommentDTO): ?Comment {
        $currentUserId = $newCommentDTO->authorId();

        $articleId = $this->model->
        where('slug', $newCommentDTO->slug()->value() )
        ->select('id')
        ->firstOrFail();


        $data = [
            'article_id' => $articleId->id,
            'user_id' => $currentUserId->value(),
            'body' => $newCommentDTO->body()->value(),
            'created_at' => now(),
            'updated_at' => now(),
        ];

        $commentModel = EloquentCommentModel::create($data);

        return $this->commentModelToComment($commentModel, $currentUserId);
    }

    /**
     * @return array<Comment>
     */
    public function allArticleComments(ArticleSlug $articleSlug, ?UserId $userId=null): array {
        $articleModel = $this->model
            ->where('slug', $articleSlug->value())
        ->firstOrFail();

        $listComments = $articleModel->comments;

        return $listComments->map(function ($commentModel) use ($userId) {
            return $this->commentModelToComment(
                commentModel: $commentModel,
                currentUserId: $userId,
            );
        })->toArray();
    }

    public function deleteComment(DeleteCommentDTO $deleteCommentDTO): ?Comment {
        $articleModel = $this->model
            ->where('slug', $deleteCommentDTO->slug()->value() )
        ->firstOrFail();

        $commentModel = $articleModel->comments()
            ->findOrFail((int) $deleteCommentDTO->id()->value() );

        if(!$commentModel) {
            return null;
        }
        // $this->authorize('delete', $comment); // TODO no implemented

        $commentModel->delete();

        return $this->commentModelToComment($commentModel, $deleteCommentDTO->userId());
    }

    private function articleModelToArticle(EloquentArticleModel $articleModel, ?UserId $currentUserId = null): Article
    {
        $userModel = $articleModel->author;
        $following = false;

        if ($currentUserId) {
            $following = boolval($userModel->isFollowing($currentUserId->value()));
        }

        $author = new DomainProfile(
            new UserName($userModel->username),
            ($userModel->bio) ? new UserBio($userModel->bio) : null,
            ($userModel->image) ? new UserImage($userModel->image) : null,
            $following
        );

        $favoritesCount = $articleModel->favoredUsers->count();
        $favorited = false;

        if ($currentUserId) {
            $favorited = $articleModel->isFavoredByUser($currentUserId->value());
        }

        $listTags = $articleModel->tags;
        /*
        foreach ($articleModel->tags as $tag) {
        Log::debug('An informational {message2}',['message2' => $tag ]);
        };
        */
        $tags = $listTags->map(function ($tag) {
            return $tag->name;
        })->toArray();

        $article = new Article(
            new ArticleTitle($articleModel->title),
            new ArticleDescription($articleModel->description),
            new ArticleBody($articleModel->body),
            new ArticleSlug($articleModel->slug),
            new ArticleCreated($articleModel->created_at->format('Y-m-d H:i:s')),
            new ArticleUpdated($articleModel->updated_at->format('Y-m-d H:i:s')),
            $favorited,
            $favoritesCount,
            $author,
            $tags
        );

        return $article;
    }

    private function articleModelToArticleSummary($articleModel, ?UserId $currentUserId = null): ArticleSummary
    {
        $userModel = $articleModel->author;
        $following = false;

        if ($currentUserId) {
            $following = boolval($userModel->isFollowing($currentUserId->value()));
        }

        $author = new DomainProfile(
            new UserName($userModel->username),
            ($userModel->bio) ? new UserBio($userModel->bio) : null,
            ($userModel->image) ? new UserImage($userModel->image) : null,
            $following
        );

        $favoritesCount = $articleModel->favoredUsers->count();
        $favorited = false;

        if ($currentUserId) {
            $favorited = $articleModel->isFavoredByUser($currentUserId->value());
        }

        $listTags = $articleModel->tags;

        $tags = $listTags->map(function ($tag) {
            return $tag->name;
        })->toArray();

        $article = new ArticleSummary(
            new ArticleTitle($articleModel->title),
            new ArticleSlug($articleModel->slug),
            new ArticleDescription($articleModel->description),
            new ArticleCreated($articleModel->created_at->format('Y-m-d H:i:s')),
            new ArticleUpdated($articleModel->updated_at->format('Y-m-d H:i:s')),
            $favorited,
            $favoritesCount,
            $author,
            $tags
        );

        return $article;
    }

    private function commentModelToComment($commentModel, ?UserId $currentUserId = null): Comment {
        $authorModel = $commentModel->author;

        $following = false;

        if ($currentUserId) {
            $following = boolval($authorModel->isFollowing($currentUserId->value()));
        }

        $author = new DomainProfile(
            username: new UserName($authorModel->username),
            bio: ($authorModel->bio) ? new UserBio($authorModel->bio) : null,
            image: ($authorModel->image) ? new UserImage($authorModel->image) : null,
            following: $following
        );

        return new Comment(
            id: new CommentId( $commentModel->id ),
            createdAt: new CommentCreated( $commentModel->created_at->format('Y-m-d H:i:s') ),
            updatedAt: new CommentUpdated( $commentModel->updated_at->format('Y-m-d H:i:s') ),
            body: new CommentBody( $commentModel->body ),
            author: $author
        );
    }

}
