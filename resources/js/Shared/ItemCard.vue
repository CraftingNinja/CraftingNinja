<template>
    <div
        class="flex gap-2 p-3 even:bg-accent/[.05] odd:bg-accent/[.07] hover:bg-accent/[.20] rounded"
    >
        <div class="relative group">
            <ItemIcon
				:item="item"
				class="self-center"
			/>
            <slot name="after-image" />
        </div>
        <div class="flex-1 leading-none text-xl font-medium overflow-hidden">
            <div class="truncate">
                {{ item.name }}
                <slot name="after-name" />
            </div>
            <div class="flex pt-1 justify-items-end">
				<slot
					v-if="showBonusSlot"
					name="bonus"
				/>
                <template
                    v-else-if="item.recipes.length"
                    v-for="(recipe, key) in item.recipes"
                    :key="recipe.id"
                >
                    <AddToList
                        v-if="! viewOnly"
                        :item-id="item.id"
                        :item-src="item.icon"
                        :item-name="item.name"
                        :recipe-id="recipe.id"
                        class="rounded"
                        :class="{
                            'opacity-10': item.recipes.length > 0 && recipeId && recipeId !== recipe.id,
                            'bg-accent/50': item.recipes.length > 0 && recipeId && recipeId === recipe.id
                        }"
                    >
                        <img
                            class="inline w-5 h-5"
                            :src='asset(`classjob/${jobs[recipe.job_id]?.name.toLowerCase() || "doh"}.png`)'
                            :alt='`Icon of ${jobs[recipe.job_id]?.name}`'
                        >
                    </AddToList>
                    <div
                        v-else-if="! recipeId || recipeId === recipe.id"
                    >
                        <!-- View Only Img -->
                        <img
                            class="inline w-5 h-5"
                            :src='asset(`classjob/${jobs[recipe.job_id]?.name.toLowerCase() || "doh"}.png`)'
                            :alt='`Icon of ${jobs[recipe.job_id]?.name}`'
                        >
                    </div>

                    <template v-if="key === item.recipes.length - 1">
                        <span
                            v-if="jobs[recipe.job_id] !== undefined"
                            class="text-sky-300 text-lg leading-[20px] pl-1"
                        >
                            {{ recipe.recipe_level }}
                        </span>
                        <RecipeStars
                            :stars="recipe.stars"
                            class="ml-1"
                        />
                    </template>
                </template>
            </div>
        </div>
        <slot>
            <AddToList
                v-if="! viewOnly"
                class="self-center"
                :item-id="item.id"
                :item-src="item.icon"
                :item-name="item.name"
            />
        </slot>
    </div>
</template>

<script setup>
import { usePage } from "@inertiajs/vue3";
import ItemIcon from "./ItemIcon.vue";
import RecipeStars from "@/Shared/RecipeStars.vue";
import AddToList from "@/Shared/List/AddToList.vue";
import { asset } from "@/Shared/Helpers/assets.js";

defineProps({
    item: Object,
    recipeId: Number,
    viewOnly: Boolean,
	showBonusSlot: Boolean
})

const jobs = usePage().props.jobs;
</script>
