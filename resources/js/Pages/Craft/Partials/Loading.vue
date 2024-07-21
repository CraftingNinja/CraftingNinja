<template>
	<div class="text-center py-8">
		<Spinner class="w-8 h-8 inline" />
		<p>
			Loading {{ Object.keys(items).length }} of {{ wanted.length }} items.
		</p>
		<div class="w-1/2 mx-auto bg-gray-200 rounded-full h-2.5 dark:bg-gray-700">
			<div
				class="bg-accent rounded-full text-xs text-center p-0.5 leading-none font-medium"
				:style="`width: ${loadedPercentage}%;`"
			>
				{{ loadedPercentage }}%
			</div>
		</div>
	</div>
</template>

<script setup>
import { computed } from "vue";
import Spinner from "@S/Spinner.vue";
import ItemsComposable from "@S/Loaders/items.js";
const { wanted, items } = ItemsComposable();
const loadedPercentage = computed(() => parseInt((Object.keys(items.value).length / wanted.value.length) * 100));
</script>
