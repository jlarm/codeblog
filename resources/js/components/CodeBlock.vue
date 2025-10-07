<script setup lang="ts">
import { computed, onMounted, ref } from 'vue';
import { codeToHtml } from 'shiki';

interface Props {
	code: string;
	language?: string;
	filename?: string;
}

const props = withDefaults(defineProps<Props>(), {
	language: 'plaintext',
	filename: '',
});

const highlightedCode = ref('');
const isLoading = ref(true);
const isCopied = ref(false);

onMounted(async () => {
	try {
		highlightedCode.value = await codeToHtml(props.code, {
			lang: props.language,
			theme: 'github-dark',
		});
	} catch (error) {
		console.error('Failed to highlight code:', error);
		highlightedCode.value = `<pre><code>${escapeHtml(props.code)}</code></pre>`;
	} finally {
		isLoading.value = false;
	}
});

function escapeHtml(text: string): string {
	const map: Record<string, string> = {
		'&': '&amp;',
		'<': '&lt;',
		'>': '&gt;',
		'"': '&quot;',
		"'": '&#039;',
	};

	return text.replace(/[&<>"']/g, (m) => map[m]);
}

const languageLabel = computed(() => {
	const labels: Record<string, string> = {
		javascript: 'JavaScript',
		typescript: 'TypeScript',
		php: 'PHP',
		python: 'Python',
		java: 'Java',
		csharp: 'C#',
		go: 'Go',
		rust: 'Rust',
		ruby: 'Ruby',
		html: 'HTML',
		css: 'CSS',
		sql: 'SQL',
		bash: 'Bash',
		json: 'JSON',
		yaml: 'YAML',
		markdown: 'Markdown',
		vue: 'Vue',
	};

	return labels[props.language] || props.language.toUpperCase();
});

async function copyToClipboard() {
	try {
		// Try modern Clipboard API first (requires HTTPS)
		if (navigator.clipboard && navigator.clipboard.writeText) {
			await navigator.clipboard.writeText(props.code);
		} else {
			// Fallback for non-HTTPS contexts
			const textarea = document.createElement('textarea');
			textarea.value = props.code;
			textarea.style.position = 'fixed';
			textarea.style.opacity = '0';
			document.body.appendChild(textarea);
			textarea.select();
			document.execCommand('copy');
			document.body.removeChild(textarea);
		}

		isCopied.value = true;
		setTimeout(() => {
			isCopied.value = false;
		}, 2000);
	} catch (error) {
		console.error('Failed to copy code:', error);
	}
}
</script>

<template>
	<div class="code-block-wrapper not-prose my-6">
		<div class="flex items-center justify-between rounded-t-lg border border-gray-700 bg-gray-800 px-4 py-2">
			<div class="flex items-center gap-2">
				<span v-if="filename" class="font-mono text-sm text-gray-300">{{ filename }}</span>
				<span v-if="filename && language" class="text-gray-500">•</span>
				<span class="text-xs uppercase tracking-wide text-gray-400">{{ languageLabel }}</span>
			</div>

			<button
				@click="copyToClipboard"
				class="flex items-center gap-2 rounded px-3 py-1 text-sm text-gray-300 transition-colors hover:bg-gray-700 hover:text-white"
				:class="{ 'text-green-400': isCopied }"
			>
				<svg
					v-if="!isCopied"
					xmlns="http://www.w3.org/2000/svg"
					class="size-4"
					fill="none"
					viewBox="0 0 24 24"
					stroke="currentColor"
				>
					<path
						stroke-linecap="round"
						stroke-linejoin="round"
						stroke-width="2"
						d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"
					/>
				</svg>
				<svg
					v-else
					xmlns="http://www.w3.org/2000/svg"
					class="size-4"
					fill="none"
					viewBox="0 0 24 24"
					stroke="currentColor"
				>
					<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
				</svg>
				<span>{{ isCopied ? 'Copied!' : 'Copy' }}</span>
			</button>
		</div>

		<div
			v-if="isLoading"
			class="flex items-center justify-center rounded-b-lg border border-t-0 border-gray-700 bg-gray-900 p-8"
		>
			<div class="flex items-center gap-2 text-gray-400">
				<svg
					class="size-5 animate-spin"
					xmlns="http://www.w3.org/2000/svg"
					fill="none"
					viewBox="0 0 24 24"
				>
					<circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
					<path
						class="opacity-75"
						fill="currentColor"
						d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"
					></path>
				</svg>
				<span class="text-sm">Loading syntax highlighting...</span>
			</div>
		</div>

		<div
			v-else
			class="overflow-x-auto rounded-b-lg border border-t-0 border-gray-700 [&>pre]:!my-0 [&>pre]:!rounded-none [&>pre]:!border-0"
			v-html="highlightedCode"
		></div>
	</div>
</template>

<style scoped>
.code-block-wrapper :deep(pre) {
	padding: 1rem;
	overflow-x: auto;
	font-size: 0.875rem;
	line-height: 1.5;
}

.code-block-wrapper :deep(code) {
	font-family: 'Fira Code', 'Consolas', 'Monaco', 'Courier New', monospace;
}
</style>
