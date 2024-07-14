<template>
	<ItemCard
		:item="item"
		:view-only="true"
		:show-bonus-slot="item.preference.type !== 'recipes'"
	>
		<template #after-name>
			<span class="text-base">
				<span class="text-gray-600">x</span>
				<span class="font-bold font-mono ml-0.5">
					{{ item.needed }}
				</span>
			</span>
		</template>
		<template #bonus>
			<div class="text-sm">
				<template v-if="item.preference.type === 'mobs'">
					<div class="space-x-2">
						<img
							class="inline w-5 h-5"
							:src="asset('gathering/fight.png')"
						/>
						<span class="capitalize">{{ item.preference.entity.name }}</span>
						<span>
						<span class="text-gray-600">lv</span><span class="text-sky-300">{{ item.preference.entity.level }}</span>
					</span>
					</div>
					<div class="space-x-2">
						<span>
							{{ item.preference.entity.location.name }}
						</span>
					</div>
				</template>
				<template v-else-if="item.preference.type === 'shops'">
					<div class="space-x-2">
						<img
							class="inline w-5 h-5"
							:src="asset('gathering/vendor.png')"
						/>
						<span class="capitalize">{{ item.preference.entity.npcs[0]?.name || item.preference.entity.name }}</span>
					</div>
					<div class="space-x-2">
						<span>
							<template v-if="item.preference.entity.npcs[0]">
								<template v-if="item.preference.entity.npcs[0].location?.parent?.name">
									{{ item.preference.entity.npcs[0].location?.parent?.name }}
								</template>
								<template v-if="item.preference.entity.npcs[0].location?.name">
									- {{ item.preference.entity.npcs[0].location?.name }}
								</template>
								<template v-if="item.preference.entity.npcs[0].x">
									&mdash; <span class="whitespace-nowrap">{{ item.preference.entity.npcs[0].x }} x {{ item.preference.entity.npcs[0].y }}</span>
								</template>
							</template>
						</span>
					</div>
				</template>
				<template v-else-if="item.preference.type === 'nodes'">
					<div class="space-x-2">
						<!-- TODO 1 - with a timer? -->
						<img
							class="inline w-5 h-5"
							:src="asset(`gathering/${nodeTypeToAssetName[item.preference.entity.type]}.png`)"
						/>
						<span class="capitalize">{{ item.preference.entity.name }}</span>
						<span>
							<span class="text-gray-600">lv</span><span class="text-sky-300">{{ item.preference.entity.level }}</span>
						</span>
					</div>
					<div class="space-x-2">
						<span>
							{{ item.preference.entity.zone.name }}
							&mdash;
							<span class="whitespace-nowrap">{{ item.preference.entity.coordinates }}</span>
						</span>
					</div>
				</template>
				<template v-else-if="item.preference.type === 'fishing'">
					<div class="space-x-2">
						<img
							class="inline w-5 h-5"
							:src="asset('gathering/fishing.png')"
						/>
						<span class="capitalize">{{ item.preference.entity.name }}</span>
						<span>
							<span class="text-gray-600">lv</span><span class="text-sky-300">{{ item.preference.entity.level }}</span>
						</span>
					</div>
					<div class="space-x-2">
						<span>
							{{ item.preference.entity.zone.name }}
						</span>
					</div>
				</template>
			</div>
		</template>
		<template #default>
			<div class="flex flex-col h-full justify-between">
				<button
					type="button"
					class="block w-8 h-8 rounded-md border-2 border-accent group"
					@click="obtained(item)"
				>
					<CheckMarkIcon
						class="w-6 h-6 mx-auto text-white invisible group-hover:visible"
					/>
				</button>
				<button
					type="button"
					class="block w-8 h-8"
					@click="openInformationSlideOver(item)"
				>
					<SignIcon
						class="inline w-5 h-5 text-gray-600 hover:text-white"
					/>
				</button>
			</div>
		</template>
	</ItemCard>
</template>

<script setup>
import { asset } from "@/Shared/Helpers/assets.js";
import ItemCard from "@/Shared/ItemCard.vue";
import CheckMarkIcon from "~icons/game-icons/check-mark";
import SignIcon from "~icons/game-icons/wooden-sign";

defineProps({
	item: Object,
});

const emit = defineEmits(['informationPane']);

const obtained = (item) => (item.obtained = item.needed);
const nodeTypeToAssetName = [
	'mining', // Node Type 0 is golden pick
	'quarrying', // Node Type 1 is blue pick
	'logging', // 2 is golden feather
	'harvesting', // 3 is blue feather
];

const openInformationSlideOver = (item) => emit('informationPane', item);
</script>
