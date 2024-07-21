<template>
	<button
		ref="button"
		type="button"
		@click="addToList(itemId, 1, recipeId, itemName, itemSrc, button)"
        class="group relative"
	>
        <slot>
            <Counter
                :number="number"
                class="absolute -top-2 -right-2 bg-primary text-white"
            />
            <AddToListIcon
                class="w-[20px] h-[20px] dark:text-gray group:hover:text-accent group:dark:hover:text-accent"
            />
        </slot>
	</button>
</template>

<script setup>
import { computed, ref } from "vue";
import Counter from "@S/Counter.vue";
import AddToListIcon from "~icons/lucide/scroll-text";
import Composable from "./composable.js";
import ListComposable from "@S/List/composable.js";

const { addToList } = Composable();
const { quantityInCart } = ListComposable();
const button = ref(null);

const props = defineProps({
	itemId: Number,
	itemSrc: String,
	itemName: String,
    recipeId: {
        type: Number,
        default: null
    },
	quantity: {
		type: Number,
		default: 1
	}
});

const number = computed(() => quantityInCart(props.itemId));
</script>
