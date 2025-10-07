<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import CodeBlock from '@/components/CodeBlock.vue';
import ContentRenderer from '@/components/ContentRenderer.vue';

interface CodeSnippet {
	language: string;
	code: string;
	filename?: string;
}

interface Post {
	id: number;
	title: string;
	slug: string;
	excerpt: string | null;
	content: string;
	code_snippets: CodeSnippet[] | null;
	published_at: string;
}

interface Props {
	post: Post;
}

defineProps<Props>();

function formatDate(date: string): string {
	return new Date(date).toLocaleDateString('en-US', {
		year: 'numeric',
		month: 'long',
		day: 'numeric',
	});
}
</script>

<template>
	<Head :title="post.title" />

	<div class="min-h-screen bg-gray-50">
		<div class="mx-auto max-w-4xl px-4 py-12">
			<nav class="mb-8">
				<Link href="/posts" class="text-sm font-medium text-blue-600 hover:text-blue-700">
					← Back to all posts
				</Link>
			</nav>

			<article class="rounded-lg border border-gray-200 bg-white p-8">
				<header class="mb-8 border-b border-gray-200 pb-8">
					<h1 class="text-4xl font-bold text-gray-900">{{ post.title }}</h1>

					<div class="mt-4 flex items-center gap-4 text-sm text-gray-500">
						<time>{{ formatDate(post.published_at) }}</time>
					</div>

					<p v-if="post.excerpt" class="mt-4 text-lg text-gray-600">
						{{ post.excerpt }}
					</p>
				</header>

				<div class="prose prose-gray max-w-none">
					<ContentRenderer :content="post.content" />
				</div>

				<div v-if="post.code_snippets && post.code_snippets.length > 0" class="mt-8">
					<h2 class="mb-4 text-2xl font-bold text-gray-900">Code Examples</h2>

					<CodeBlock
						v-for="(snippet, index) in post.code_snippets"
						:key="index"
						:code="snippet.code"
						:language="snippet.language"
						:filename="snippet.filename"
					/>
				</div>
			</article>
		</div>
	</div>
</template>
