<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Article;
use App\Models\User;
use Illuminate\Support\Facades\DB;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Article>
 */
class ArticleFactory extends Factory
{    //protected $model = Article::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
		$body = $this->faker->paragraph(30);
		$description = Str::words($body, 20, '...');

		$title = $this->faker->unique()->sentence;
		$slug = Str::of($title)->slug('-');

        return [
            'user_id' => User::inRandomOrder()->first()->id, // Asigna un usuario existente
			'title' => $title,
			'slug' => $slug,
			// 'thumbnail' => $this->faker->imageUrl,
			'description' => $description,
			'body' => $body,
			'created_at' => now(),
			'updated_at' => now(),
        ];
    }
}
