<template>
	<Popover class="relative inline-block" v-slot="{ open }">
		<!-- Use the `open` state to conditionally change the direction of the chevron icon. -->
		<PopoverButton>
			<slot />
		</PopoverButton>
		<!--DECOMMISSIONED FILE TODO 1 DELETEME-->
		<PopoverPanel class="absolute z-10 bg-gray-800 shadow-xl rounded-2xl p-4 text-left w-[350px]">
			<p class="font-semibold">
				{{ item.name }} is involved in making:
			</p>
			<div class="space-y-2 max-h-[400px] overflow-y-auto">
				<ItemCard
					v-for="item in Object.values(item.trigger)"
					:key="item.id"
					:item="item"
				>
					<!-- intentionally blank, removes add to cart -->
					&nbsp;
				</ItemCard>
			</div>
		</PopoverPanel>
	</Popover>
</template>

<script setup>
import { Popover, PopoverButton, PopoverPanel } from '@headlessui/vue'
import ItemCard from "@/Shared/ItemCard.vue";
defineProps({
	item: Object
});
</script>
