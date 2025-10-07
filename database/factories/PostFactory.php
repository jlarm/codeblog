<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Post>
 */
class PostFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $title = fake()->sentence();

        return [
            'title' => $title,
            'slug' => \Illuminate\Support\Str::slug($title),
            'excerpt' => fake()->paragraph(),
            'content' => fake()->paragraphs(5, true),
            'code_snippets' => [
                [
                    'language' => 'php',
                    'code' => "<?php\n\nnamespace App\\Models;\n\nclass Example\n{\n    public function demo(): string\n    {\n        return 'Hello World';\n    }\n}",
                    'filename' => 'Example.php',
                ],
            ],
            'is_published' => fake()->boolean(70),
            'published_at' => fake()->dateTimeBetween('-1 year', 'now'),
        ];
    }
}
