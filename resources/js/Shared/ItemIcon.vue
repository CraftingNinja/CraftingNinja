<template>
	<img
		:src="item.icon"
		class="w-[40px] h-[40px]"
		alt="Icon for item"
		:title="item.category.name"
	/>
</template>

<script setup>
defineProps({
	item: Object
})
</script>
