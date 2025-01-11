<?php

use App\Http\Controllers\Article\CreateArticleController;
use App\Http\Controllers\Article\DeleteArticleController;
use App\Http\Controllers\Article\FavoriteArticleController;
use App\Http\Controllers\Article\FeedArticlesController;
use App\Http\Controllers\Article\GetAllArticlesController;
use App\Http\Controllers\Article\GetArticleController;
use App\Http\Controllers\Article\UpdateArticleController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/
/*
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
*/

use App\Http\Controllers\AuthController;
use App\Http\Controllers\TodoController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Comment\AddCommentController;
use App\Http\Controllers\Comment\DeleteCommentController;
use App\Http\Controllers\Comment\GetAllCommentsController;
use App\Http\Controllers\Profile\FollowProfileController;
use App\Http\Controllers\Profile\GetProfileController;
use App\Http\Controllers\Tag\GetTagsController;
use App\Http\Controllers\User\GetCurrentUserController;
use App\Http\Controllers\User\UpdateCurrentUserController;

Route::controller(AuthController::class)->group(function () {
    Route::post('login', 'login');
    Route::post('register', 'register');
    Route::post('logout', 'logout');
    Route::post('refresh', 'refresh');

});

Route::middleware(['auth.jwt'])->group(function () {
    // Protected routes
Route::controller(TodoController::class)->group(function () {
    Route::get('todos', 'index');
    Route::post('todo', 'store');
    Route::get('todo/{id}', 'show');
    Route::put('todo/{id}', 'update');
    Route::delete('todo/{id}', 'destroy');
    });

    Route::post('user', GetCurrentUserController::class);
    Route::put('user', UpdateCurrentUserController::class);
    Route::post('profiles/{username}', [FollowProfileController::class, 'followProfile']);
    Route::delete('profiles/{username}', [FollowProfileController::class, 'unfollowProfile']);

    Route::post('articles/{slug}/favorite', [FavoriteArticleController::class, 'favoriteArticle']);
    Route::delete('articles/{slug}/favorite', [FavoriteArticleController::class, 'unfavoriteArticle']);
    Route::get('articles/feed', FeedArticlesController::class);

    Route::post('articles', CreateArticleController::class);
    Route::put('articles', UpdateArticleController::class);

    Route::delete('articles/{slug}', DeleteArticleController::class);

    Route::post('articles/{slug}/comments', AddCommentController::class);
    Route::delete('articles/{slug}/comments/{id}', DeleteCommentController::class);
});

Route::get('profiles/{username}', GetProfileController::class);

Route::get('articles', GetAllArticlesController::class);
Route::get('articles/{slug}', GetArticleController::class);
Route::get('articles/{slug}/comments', GetAllCommentsController::class);

Route::get('tags', GetTagsController::class);

Route::post('users/login', LoginController::class);
Route::post('users', RegisterController::class);
/*
Route::post('users/login', function() {
        return "Welcome to our homepage";
});
*/

/*
Route::get('user/{id}', 'GetUserController');
Route::post('user', 'CreateUserController');
Route::put('user/{id}', 'UpdateUserController');
Route::delete('user/{id}', 'DeleteUserController');
*/

