<script setup lang="ts">
import { computed, h, VNode } from 'vue';
import { marked } from 'marked';
import CodeBlock from './CodeBlock.vue';

interface Props {
	content: string;
}

const props = defineProps<Props>();

interface CodeBlockData {
	language: string;
	code: string;
	filename?: string;
}

function parseContent(markdown: string): { type: 'html' | 'code'; content: string | CodeBlockData }[] {
	const parts: { type: 'html' | 'code'; content: string | CodeBlockData }[] = [];

	// Configure marked to preserve code blocks with special markers
	const tokens = marked.lexer(markdown);
	let currentHtml = '';

	for (const token of tokens) {
		if (token.type === 'code') {
			// Save any accumulated HTML
			if (currentHtml.trim()) {
				parts.push({ type: 'html', content: currentHtml });
				currentHtml = '';
			}

			// Add code block
			const language = token.lang || 'plaintext';
			const code = token.text;

			parts.push({
				type: 'code',
				content: { language, code },
			});
		} else {
			// Convert other tokens to HTML
			currentHtml += marked.parser([token]);
		}
	}

	// Add any remaining HTML
	if (currentHtml.trim()) {
		parts.push({ type: 'html', content: currentHtml });
	}

	return parts;
}

const contentParts = computed(() => parseContent(props.content));

function renderParts(): VNode[] {
	return contentParts.value.map((part, index) => {
		if (part.type === 'html') {
			return h('div', {
				key: `html-${index}`,
				innerHTML: part.content as string,
			});
		} else {
			const data = part.content as CodeBlockData;
			return h(CodeBlock, {
				key: `code-${index}`,
				code: data.code,
				language: data.language,
				filename: data.filename,
			});
		}
	});
}
</script>

<template>
	<div>
		<component :is="() => renderParts()" />
	</div>
</template>
