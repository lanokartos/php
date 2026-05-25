<?php

namespace Database\Factories;

use App\Models\BlogPost;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

/**
 * @extends Factory<BlogPost>
 */
class BlogPostFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var class-string<BlogPost>
     */
    protected $model = BlogPost::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $title = $this->faker->sentence(rand(3, 8), true);
        // Генеруємо випадковий великий текст статті (від 1000 до 4000 символів)
        $txt = $this->faker->realText(rand(1000, 4000));
        // Створюємо випадкову дату створення статті в проміжку між 3 та 2 місяцями тому
        $date = $this->faker->dateTimeBetween('-3 months', '-2 months');
        $hasSlugColumn = Schema::hasColumn((new BlogPost())->getTable(), 'slug');

        $attributes = [
            'category_id'   => rand(1, 11), // випадкова категорія від 1 до 11
            'user_id'       => (rand(1, 5) == 5) ? 1 : 2, // у 20% випадків автор №1, у 80% — автор №2
            'title'         => $title,
            'excerpt'       => $this->faker->text(rand(40, 100)), // короткий анонс статті
            'content_raw'   => $txt,
            'content_html'  => $txt, // у реальному проєкті тут був би конвертований HTML, зараз просто дублюємо текст
            'is_published'  => rand(1, 5) > 1, // 80% статей будуть опубліковані, 20% — залишаться чернетками
            'published_at'  => rand(1, 5) > 1 ? $date : null, // якщо опубліковано — ставимо дату, якщо ні — null
            'created_at'    => $date,
            'updated_at'    => $date,
        ];

        if ($hasSlugColumn) {
            $attributes['slug'] = Str::slug($title) . '-' . Str::lower(Str::random(6)); // гарантує унікальний URL-вигляд
        }

        return $attributes;
    }
}
