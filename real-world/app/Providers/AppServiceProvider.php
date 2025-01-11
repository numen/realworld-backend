<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

use User\Domain\Repositories\UserRepositoryInterface;
use User\Infrastructure\Repositories\EloquentUserRepository;

use Article\Domain\Repositories\ArticleRepositoryInterface;
use Article\Infrastructure\Repositories\EloquentArticleRepository;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(UserRepositoryInterface::class, EloquentUserRepository::class);
        $this->app->bind(ArticleRepositoryInterface::class, EloquentArticleRepository::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
