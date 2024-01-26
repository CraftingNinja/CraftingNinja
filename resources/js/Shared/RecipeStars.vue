<template>
	<div
		v-if="stars > 0"
		class="inline-block h-5 relative"
	>
		<StarIcon class="w-4 h-4" />
		<StarIcon v-if="stars > 1" class="w-4 h-4 absolute top-0 left-3" />
		<StarIcon v-if="stars > 2" class="w-4 h-4 absolute top-0 left-6" />
		<StarIcon v-if="stars > 3" class="w-4 h-4 absolute top-0 left-9" />
	</div>
</template>

<script setup>
import StarIcon from "~icons/material-symbols/star";
defineProps({
	stars: Number
});
const distance = 2.5;
</script>
