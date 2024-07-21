<template>
    <Combobox v-model="selected" nullable>
        <div class="relative mt-1">
            <div
                class="relative w-full cursor-default overflow-hidden rounded-lg bg-white text-left shadow-md focus:outline-none focus-visible:ring-2 focus-visible:ring-white/75 focus-visible:ring-offset-2 focus-visible:ring-offset-teal-300 sm:text-sm"
            >
                <ComboboxInput
                    class="w-full border-none py-2 pl-3 pr-10 text-sm leading-5 text-gray-900 focus:ring-0"
                    :displayValue="(location) => location?.name ?? '** Filter by zone **'"
                    @change="query = $event.target.value"
                />
                <ComboboxButton
                    class="absolute inset-y-0 right-0 flex items-center pr-2"
                >
                    <ChevronUpDownIcon
                        class="h-5 w-5 text-gray-400"
                        aria-hidden="true"
                    />
                </ComboboxButton>
            </div>
            <TransitionRoot
                leave="transition ease-in duration-100"
                leaveFrom="opacity-100"
                leaveTo="opacity-0"
                @after-leave="query = ''"
            >
                <ComboboxOptions
                    class="absolute mt-1 max-h-60 w-full overflow-auto rounded-md bg-white py-1 text-base shadow-lg ring-1 ring-black/5 focus:outline-none sm:text-sm"
                >
                    <div
                        v-if="filteredLocations.length === 0 && query !== ''"
                        class="relative cursor-default select-none px-4 py-2 text-gray-700"
                    >
                        Nothing found.
                    </div>

                    <ComboboxOption
                        v-for="location in filteredLocations"
                        as="template"
                        :key="location.name"
                        :value="location"
                        v-slot="{ selected, active }"
                    >
                        <li
                            class="relative cursor-default select-none py-2 pl-10 pr-4"
                            :class="{
                                'bg-teal-600 text-white': active,
                                'text-gray-900': !active,
                            }"
                        >
                            <span
                                class="block truncate"
                                :class="{ 'font-medium': selected, 'font-normal': !selected }"
                            >
                                {{ location.name }}
                                <span class="space-x-2 ml-2">
                                    <span v-if="location.itemCount > 0">
                                        <img
                                            :src="items[Object.keys(location.data)[0]].icon"
                                            class="w-5 h-5 inline"
                                            alt="Icon for item"
                                        />
                                    </span>
                                    <span v-if="location.itemCount > 1">
                                        <img
                                            :src="items[Object.keys(location.data)[1]].icon"
                                            class="w-5 h-5 inline"
                                            alt="Icon for item"
                                        />
                                    </span>
                                    <span v-if="location.itemCount > 2">
                                        +{{ location.itemCount - 2 }}
                                    </span>
                                </span>
                            </span>
                            <span
                                v-if="selected"
                                class="absolute inset-y-0 left-0 flex items-center pl-3"
                                :class="{ 'text-white': active, 'text-teal-600': !active }"
                            >
                                <CheckIcon class="h-5 w-5" aria-hidden="true" />
                            </span>
                        </li>
                    </ComboboxOption>
                </ComboboxOptions>
            </TransitionRoot>
        </div>
    </Combobox>
</template>

<script setup>
import { ref, computed } from 'vue'
import {
    Combobox,
    ComboboxInput,
    ComboboxButton,
    ComboboxOptions,
    ComboboxOption,
    TransitionRoot,
} from '@headlessui/vue'
import { CheckIcon, ChevronUpDownIcon } from '@heroicons/vue/20/solid'
import ItemsComposable from "@S/Loaders/items.js";

const { items } = ItemsComposable();

const props = defineProps({
    locations: Object,
});

let selected = ref(null)
let query = ref('')

// Locations comes across with the name as a key and data as a value; get it into a combobox format
let filteredLocations = computed(() => {
    const vals = [{
        name: '** Clear Selection **',
        data: {}
    }];

    Object.entries(props.locations).forEach(([name, data]) => {
        vals.push({
            name,
            data,
            itemCount: Object.keys(data).length,
        });
    });

    return query.value === ''
        ? vals
        : vals.filter((location) =>
            location.name
                .toLowerCase()
                .replace(/\s+/g, '')
                .includes(query.value.toLowerCase().replace(/\s+/g, ''))
        );
})
</script>
