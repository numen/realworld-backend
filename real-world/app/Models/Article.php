<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

class Article extends Model
{

    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'description',
        'body',
        'user_id',
    ];

    public function tags()
    {
        return $this->belongsToMany(Tag::class);
    }

    /**
     * The favorites that belong to the article.
     */
    public function favoredUsers(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'favorite_articles');
    }

    /*
    * Determine if user favored the article.
    */
    public function isFavoredByUser(string $userId): bool
    {
        return $this->favoredUsers()
            ->where('user_id', $userId)
            ->exists();
    }

    /**
     * Article author.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo<User, self>
     */
    public function author()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function scopeWithAuthor($query)
    {
        return $query->select('id', 'title', 'created_at', 'updated_at') // Especifica los campos que deseas
            ->with('author');
    }

    public function scopeListA(Builder $builder, int $limit, int $offset): Builder
    {
        return $builder->latest()
            ->select('id', 'slug', 'title', 'description', 'created_at', 'updated_at', 'user_id')
            ->limit($limit)
            ->offset($offset);
    }

    public function scopeList(Builder $builder, ?string $currentUserId = "null", int $limit, int $offset): Builder
    {
        /*
        return $builder->latest()
            ->limit($limit)
            ->offset($offset)
            ->select('slug', 'title', 'description',
                DB::raw('created_at as createdAt'),
                DB::raw('updated_at as updatedAt')
            )
            ->with('author');
            exit;

            */
        /*
                $users = $builder->select(
                            "users.id",
                            "users.name",
                            "users.email",
                            "countries.name as country_name"
                        )
        ->leftJoin("countries", "countries.id", "=", "users.country_id");
        */
        //$currentUserId = "9dda022a-16f5-4e3f-a712-2ae88b04629a";
        /*
        return $builder->select('slug', 'title', 'description','created_at as createdAt', 'updated_at as updatedAt', 'user_id') // Especifica los campos que deseas
            ->limit($limit)
            ->offset($offset)
            ->with(['author:id,username,bio,image'])
            ; //->where('author_id', $authorId); // Especifica los campos que deseas de Author
        */

        /*   return DB::table('articles')
    ->join('authors', 'articles.author_id', '=', 'authors.id')
            ->select('articles.id', 'articles.title', 'authors.name as author_name');
        */
        return $builder->join('users', 'articles.user_id', '=', 'users.id')

            ->leftJoin('followers', function ($join) use ($currentUserId) {
                $join->on('followers.following_id', '=', 'users.id')
                    ->where('followers.follower_id', '=', $currentUserId);
            })

            ->leftJoin('favorite_articles as fa_user', function ($join) use ($currentUserId) {
                $join->on('fa_user.article_id', '=', 'articles.id')
                    ->where('fa_user.user_id', '=', $currentUserId);
            })
            ->select(
                'articles.slug',
                'articles.title',
                'articles.description',
                'articles.created_at',
                'articles.updated_at',
                DB::raw('IF(fa_user.user_id IS NOT NULL, 1, 0) as is_favorited'),
                'users.username as author_username',
                'users.bio as author_bio',
                'users.image as author_image',
                DB::raw('IF(followers.follower_id IS NOT NULL, 1, 0) as author_is_following')
            )
            ->selectRaw('(SELECT COUNT(*) FROM favorite_articles fa WHERE fa.article_id = articles.id) as favorites_count')

            //->leftJoin('favorite_articles as fa', 'articles.id', '=', 'fa.article_id')

            //->groupBy('articles.id')
            //DB::Raw('COUNT(fa.article_id) as favorites_count'),

            //->selectRaw('COUNT(fa.article_id) as favorites_count')
            ->orderBy('articles.created_at', 'desc')
            ->limit($limit)
            ->offset($offset);
    }

    public function scopeOfAuthor(Builder $query, string $username): Builder
    {
        return $query->whereHas(
            'author',
            fn (Builder $builder) =>
            $builder->where('username', $username)
        );
    }

    public function scopeOfTag(Builder $query, string $tagName): Builder
    {
        return $query->whereHas(
            'tags',
            fn (Builder $builder) =>
            $builder->where('name', $tagName)
        );
    }

    public function scopeFavoredByUser(Builder $query, string $username): Builder
    {
        return $query->whereHas(
            'favoredUsers',
            fn (Builder $builder) =>
            $builder->where('username', $username)
        );
    }

    public function scopeFollowedAuthorsOf(Builder $query, User $user): Builder
    {
        return $query->whereHas('author', fn (Builder $builder) =>
            $builder->whereIn('user_id', $user->following()->pluck('following_id'))
        );
    }

    /**
     * Attach tags to article.
     *
     * @param array<string> $tags
     */
    public function attachTags(array $tags): void
    {
        foreach ($tags as $tagName) {
            $tag = Tag::firstOrCreate([
                'name' => $tagName,
            ]);

            $this->tags()->syncWithoutDetaching($tag);
        }
    }

    public function comments()
    {
        return $this->hasMany(Comment::class, 'article_id');
    }


}
