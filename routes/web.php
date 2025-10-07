<?php

use App\Models\Post;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    $categorySlug = request('category');

    $posts = Post::query()
        ->with('category:id,name,slug')
        ->where('is_published', true)
        ->when($categorySlug, function ($query, $categorySlug) {
            $query->whereHas('category', function ($query) use ($categorySlug) {
                $query->where('slug', $categorySlug);
            });
        })
        ->orderBy('published_at', 'desc')
        ->get(['id', 'title', 'slug', 'excerpt', 'published_at', 'category_id']);

    $categories = \App\Models\Category::query()
        ->whereHas('posts', function ($query) {
            $query->where('is_published', true);
        })
        ->withCount(['posts' => function ($query) {
            $query->where('is_published', true);
        }])
        ->orderBy('name')
        ->get(['id', 'name', 'slug']);

    return Inertia::render('Posts/Index', [
        'posts' => $posts,
        'categories' => $categories,
        'selectedCategory' => $categorySlug,
    ]);
})->name('posts.index');

Route::get('/posts/{post:slug}', function (Post $post) {
    abort_if(! $post->is_published, 404);

    return Inertia::render('Posts/Show', [
        'post' => $post,
    ]);
})->name('posts.show');
