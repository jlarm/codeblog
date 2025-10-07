<?php

namespace Database\Seeders;

use App\Models\Post;
use Illuminate\Database\Seeder;

class PostSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Post::create([
            'title' => 'Getting Started with Laravel 12',
            'slug' => 'getting-started-with-laravel-12',
            'excerpt' => 'Learn the basics of Laravel 12 and its new streamlined structure.',
            'content' => 'Laravel 12 introduces a more streamlined application structure that makes it easier to build modern web applications. In this post, we\'ll explore the key features and improvements.

The new structure eliminates several files that were previously required, making your application cleaner and easier to understand.

## Installation

Getting started with Laravel is simple. First, make sure you have Composer installed, then run:

```bash
composer create-project laravel/laravel my-app
cd my-app
php artisan serve
```

This will create a new Laravel project and start the development server on `http://localhost:8000`.

## Routes Made Simple

Here\'s how you can define a simple route in Laravel 12:

```php
<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get(\'/\', function () {
    return Inertia::render(\'Welcome\');
})->name(\'home\');
```

The routing is intuitive and powerful, allowing you to build complex applications with ease.

### API Routes

You can also define API routes easily:

```php
Route::get(\'/api/users\', function () {
    return User::all();
});
```',
            'code_snippets' => [
                [
                    'language' => 'php',
                    'filename' => 'bootstrap/app.php',
                    'code' => "<?php

use Illuminate\Foundation\Application;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->create();",
                ],
            ],
            'is_published' => true,
            'published_at' => now()->subDays(5),
        ]);

        Post::create([
            'title' => 'Building Type-Safe Routes with Wayfinder',
            'slug' => 'building-type-safe-routes-with-wayfinder',
            'excerpt' => 'Discover how Laravel Wayfinder brings type safety to your frontend routing.',
            'content' => 'Laravel Wayfinder auto-generates TypeScript route definitions from your Laravel routes, ensuring type safety across your entire application.

Say goodbye to hardcoded URLs and hello to autocomplete and type checking!

## How It Works

Wayfinder analyzes your Laravel routes and generates TypeScript functions with proper types. Here\'s a simple example:

```typescript
import { Link } from \'@inertiajs/vue3\';
import { usersShow } from \'@/routes\';

// Type-safe route generation with autocomplete
const userLink = usersShow({ user: 123 });
```

The generated routes include all parameters, and TypeScript will warn you if you miss any required parameters or use the wrong type.

## Configuration

Add Wayfinder to your `vite.config.ts`:

```typescript
import { wayfinder } from \'@laravel/vite-plugin-wayfinder\';

export default defineConfig({
    plugins: [
        laravel({
            input: [\'resources/js/app.ts\'],
        }),
        wayfinder(),
    ],
});
```',
            'code_snippets' => [],
            'is_published' => true,
            'published_at' => now()->subDays(2),
        ]);

        Post::create([
            'title' => 'Advanced Filament Forms with Repeaters',
            'slug' => 'advanced-filament-forms-with-repeaters',
            'excerpt' => 'Master complex form layouts using Filament\'s powerful repeater component.',
            'content' => 'Filament\'s repeater component allows you to create dynamic, repeatable form sections with ease. Perfect for managing arrays of data like contact methods, addresses, or in our case, code snippets!

## Basic Repeater

Here\'s a simple repeater for managing tags:

```php
Repeater::make(\'tags\')
    ->schema([
        TextInput::make(\'name\')
            ->required(),
    ])
    ->addActionLabel(\'Add Tag\')
    ->defaultItems(0);
```

## Advanced Repeater with Multiple Fields

For more complex data structures, you can add multiple fields:

```php
Repeater::make(\'code_snippets\')
    ->schema([
        TextInput::make(\'filename\')
            ->label(\'Filename\'),

        Select::make(\'language\')
            ->options([
                \'php\' => \'PHP\',
                \'javascript\' => \'JavaScript\',
                \'typescript\' => \'TypeScript\',
            ])
            ->required(),

        Textarea::make(\'code\')
            ->required()
            ->rows(10),
    ])
    ->collapsible()
    ->itemLabel(fn (array $state) => $state[\'filename\'] ?? \'Code Snippet\')
    ->reorderable()
    ->addActionLabel(\'Add Code Snippet\');
```

The repeater supports collapsing, reordering, and custom labels for each item.',
            'code_snippets' => [],
            'is_published' => true,
            'published_at' => now()->subDay(),
        ]);
    }
}
