<script setup lang="ts">
import { Head, Link, router } from '@inertiajs/vue3';

interface Category {
	id: number;
	name: string;
	slug: string;
	posts_count: number;
}

interface Post {
	id: number;
	title: string;
	slug: string;
	excerpt: string | null;
	published_at: string;
	category?: {
		id: number;
		name: string;
		slug: string;
	};
}

interface Props {
	posts: Post[];
	categories: Category[];
	selectedCategory?: string;
}

defineProps<Props>();

function formatDate(date: string): string {
	return new Date(date).toLocaleDateString('en-US', {
		year: 'numeric',
		month: 'long',
		day: 'numeric',
	});
}

function filterByCategory(categorySlug: string | null) {
	router.get(
		'/posts',
		categorySlug ? { category: categorySlug } : {},
		{
			preserveState: true,
			preserveScroll: true,
		},
	);
}
</script>

<template>
	<Head title="Blog Posts" />

	<div class="min-h-screen bg-gray-50">
		<div class="mx-auto max-w-4xl px-4 py-12">
			<header class="mb-12">
				<h1 class="text-4xl font-bold text-gray-900">Blog Posts</h1>
				<p class="mt-2 text-lg text-gray-600">Technical articles and tutorials</p>
			</header>

			<div v-if="categories.length > 0" class="mb-8">
				<div class="flex flex-wrap gap-2">
					<button
						@click="filterByCategory(null)"
						class="rounded-full px-4 py-2 text-sm font-medium transition-colors"
						:class="
							!selectedCategory
								? 'bg-blue-600 text-white'
								: 'bg-white text-gray-700 hover:bg-gray-100 border border-gray-300'
						"
					>
						All Posts
					</button>

					<button
						v-for="category in categories"
						:key="category.id"
						@click="filterByCategory(category.slug)"
						class="rounded-full px-4 py-2 text-sm font-medium transition-colors"
						:class="
							selectedCategory === category.slug
								? 'bg-blue-600 text-white'
								: 'bg-white text-gray-700 hover:bg-gray-100 border border-gray-300'
						"
					>
						{{ category.name }} ({{ category.posts_count }})
					</button>
				</div>
			</div>

			<div v-if="posts.length === 0" class="rounded-lg border border-gray-200 bg-white p-8 text-center">
				<p class="text-gray-500">No posts published yet.</p>
			</div>

			<div v-else class="space-y-8">
				<article
					v-for="post in posts"
					:key="post.id"
					class="rounded-lg border border-gray-200 bg-white p-6 transition hover:border-gray-300 hover:shadow-md"
				>
					<div class="mb-3">
						<span
							v-if="post.category"
							class="inline-block rounded-full bg-blue-100 px-3 py-1 text-xs font-medium text-blue-800"
						>
							{{ post.category.name }}
						</span>
					</div>

					<Link :href="`/posts/${post.slug}`" class="group">
						<h2 class="text-2xl font-bold text-gray-900 group-hover:text-blue-600">
							{{ post.title }}
						</h2>
					</Link>

					<p v-if="post.excerpt" class="mt-3 text-gray-600">
						{{ post.excerpt }}
					</p>

					<div class="mt-4 flex items-center justify-between">
						<time class="text-sm text-gray-500">{{ formatDate(post.published_at) }}</time>

						<Link :href="`/posts/${post.slug}`" class="text-sm font-medium text-blue-600 hover:text-blue-700">
							Read more →
						</Link>
					</div>
				</article>
			</div>
		</div>
	</div>
</template>
