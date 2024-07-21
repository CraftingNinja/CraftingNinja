<template>
    <TransitionRoot
        as="template"
        :show="show"
    >
        <Dialog
            as="div"
            class="relative z-10"
            @close="emit('close')"
        >
            <TransitionChild as="template" enter="ease-in-out duration-500" enter-from="opacity-0" enter-to="opacity-100" leave="ease-in-out duration-500" leave-from="opacity-100" leave-to="opacity-0">
                <div class="fixed inset-0 bg-black bg-opacity-50 transition-opacity" />
            </TransitionChild>

            <div class="fixed inset-0 overflow-hidden">
                <div class="absolute inset-0 overflow-hidden">
                    <div class="pointer-events-none fixed inset-y-0 right-0 flex max-w-full pl-10">
                        <TransitionChild as="template" enter="transform transition ease-in-out duration-500 sm:duration-700" enter-from="translate-x-full" enter-to="translate-x-0" leave="transform transition ease-in-out duration-500 sm:duration-700" leave-from="translate-x-0" leave-to="translate-x-full">
                            <DialogPanel class="pointer-events-auto relative w-screen max-w-md">
								<div class="flex h-full flex-col divide-y divide-gray-700 bg-gray-800 shadow-xl">
									<div class="bg-accent-950 px-4 py-6 sm:px-6">
										<div class="flex items-center justify-between">
											<DialogTitle class="text-xl font-semibold leading-6 text-white flex space-x-3">
												<img
													role="button"
													:src="gameAsset(item.icon)"
													class="w-[40px] h-[40px]"
													alt="Icon for item"
													:title="item.category.name"
												/>
												<div class="text-white font-medium flex-1 self-center truncate">
													{{ item.name }}
												</div>
											</DialogTitle>
											<div class="ml-3 flex h-7 items-center">
												<button
													type="button"
													class="relative rounded-md bg-accent-700 text-accent-200 hover:text-white focus:outline-none focus:ring-2 focus:ring-white"
													@click="emit('close')"
												>
													<span class="absolute -inset-2.5" />
													<span class="sr-only">Close panel</span>
													<XMarkIcon class="h-6 w-6" aria-hidden="true" />
												</button>
											</div>
										</div>
										<div class="mt-1">
											<p class="text-sm text-indigo-300">
												Need: {{ item.needed }}
											</p>
										</div>
									</div>
									<div class="h-0 flex-1 overflow-y-auto">
										<div class="relative flex-1 p-4 sm:px-6 divide-y divide-accent *:py-6 first:*:pt-0 last:*:pb-0">
											<div
												v-if="item.triggers"
												class="space-y-2"
											>
												<div>
													Reagent is involved in making:
												</div>

												<div class="flex flex-wrap gap-2">
													<ItemIcon
														v-for="trigger in item.triggers"
														:key="trigger.id"
														:item="trigger"
													/>
												</div>
											</div>

											<div>
												<div class="leading-none pb-2">
													Configure this reagent's gathering preference.
												</div>

												<RadioGroup
													v-model="selectedPreference"
												>
													<div class="mt-4 grid grid-cols-1 gap-y-4">
                                                        <p v-if="!filteredPreferences.length" class="text-amber-500">
                                                            <img
                                                                :src="gameAsset('status/confused.png')"
                                                                class="inline"
                                                                alt="Confusion"
                                                            />
                                                            Data missing. Unknown gathering information.
                                                        </p>
														<RadioGroupOption
															as="template"
															v-for="pref in filteredPreferences"
															:key="pref.identifier"
															:value="pref.identifier"
															v-slot="{ active, checked }"
															@dblclick="saveChoice(pref.identifier)"
														>
															<div
																:class="[
																	active
																		? 'border-accent ring-2 ring-accent'
																		: 'border-gray-300',
																	'relative flex cursor-pointer rounded-lg border bg-gray-800 p-4 shadow-sm focus:outline-none'
																]"
															>
																<span class="flex flex-1 space-x-1">
																	<img
																		class="inline w-5 h-5"
																		:src="gameAsset('gathering/' + pref.display.icon)"
																	/>
																	<span class="flex flex-col">
																		<RadioGroupLabel
																			as="span"
																			class="block text-lg leading-5 font-medium text-white capitalize"
																		>
																			{{ pref.display.name }}
																			<span v-if="pref.display.level">
																				<span class="text-gray-600">lv</span><span class="text-sky-300">{{ pref.display.level }}</span>
																			</span>
																		</RadioGroupLabel>
																		<RadioGroupDescription
																			as="span"
																			class="mt-1 flex items-center text-sm text-gray-300"
																		>
																			{{ pref.display.location }}
																		</RadioGroupDescription>
																		<RadioGroupDescription
																			as="span"
																			v-if="pref.display.extra"
																			class="mt-1 text-sm font-medium text-gray-300"
																		>
																			{{ pref.display.extra }}
																		</RadioGroupDescription>
																	</span>
																</span>
																<CheckCircleIcon
																	:class="[
																		!checked
																			? 'invisible'
																			: '',
																		'h-5 w-5 text-accent'
																	]"
																	aria-hidden="true"
																/>
																<span
																	:class="[
																		active
																			? 'border'
																			: 'border-2',
																		checked
																			? 'border-accent'
																			: 'border-transparent',
																		'pointer-events-none absolute -inset-px rounded-lg'
																	]"
																	aria-hidden="true"
																/>
															</div>
														</RadioGroupOption>
													</div>
												</RadioGroup>
											</div>
										</div>
									</div>
									<div class="flex flex-shrink-0 justify-end px-4 py-4">
										<button
											type="button"
											class="rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50"
											@click="emit('close')"
										>
											Cancel
										</button>
										<button
											type="button"
											class="ml-4 inline-flex justify-center rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600"
											@click="saveChoice()"
										>
											Save
										</button>
									</div>
                                </div>
                            </DialogPanel>
                        </TransitionChild>
                    </div>
                </div>
            </div>
        </Dialog>
    </TransitionRoot>
</template>

<script setup>
import { computed, ref, watch, unref } from 'vue'
import { Dialog, DialogPanel, DialogTitle, TransitionChild, TransitionRoot } from '@headlessui/vue'
import { XMarkIcon } from '@heroicons/vue/24/outline'
import { gameAsset } from "@H/assets.js";
import { RadioGroup, RadioGroupDescription, RadioGroupLabel, RadioGroupOption } from '@headlessui/vue'
import { CheckCircleIcon } from '@heroicons/vue/20/solid'
import CraftComposable from "../composable.js";
import ItemIcon from "@S/ItemIcon.vue";

const { savePreference } = CraftComposable();

const props = defineProps({
    item: Object,
    show: Boolean
});

const emit = defineEmits(['close']);
const query = ref('')
const selectedPreference = ref(null);

const nodeTypeToAssetName = [
    'mining', // Node Type 0 is golden pick
    'quarrying', // Node Type 1 is blue pick
    'logging', // 2 is golden feather
    'harvesting', // 3 is blue feather
];

const prefTypes = {
	fishing: (data) => ({
		icon: 'fishing.png',
		name: data.name,
		location: data.zone.name + (data.x ? ' (' + data.x + ', ' + data.y + ')' : ''),
		level: data.level,
	}),
	nodes: (data) => ({
		icon: nodeTypeToAssetName[data.type] + '.png',
		name: data.name,
		location: data.zone.name,
		level: data.level,
	}),
	mobs: (data) => {
		let location = data.location.name;
		if (data.location.parent?.name) {
			location = data.location.parent?.name + ' - ' + data.location.name;
		}
		return {
			icon: 'fight.png',
			name: data.name,
			location,
			level: data.level,
		};
	},
	shops: (data) => {
		const npc = data.npcs[0];
		let location = 'Generic Vendor / Unknown Location';
		if (npc) {
			if (npc?.location?.parent?.name) {
				location = npc?.location?.parent?.name;
			}
			if (npc?.location?.name) {
				location = location + ' - ' + npc?.location?.name;
			}
			if (npc?.x) {
				location = location + ' (' + npc.x + ', ' + npc.y + ')';
			}
		}
		return {
			icon: 'vendor.png',
			name: npc?.name || data.name,
			location,
			extra: null,
		};
	},
};

const filteredPreferences = computed(() => {
    // Put all the preferences in something usable
    const structuredPreferences = [];

	Object.entries(prefTypes).forEach(([type, displayCallback]) => {
		if (props.item[type]?.length) {
			Object.values(props.item[type]).forEach((data) => {
				structuredPreferences.push({
					type,
					identifier: type.charAt(0) + '|' + data.id,
					display: displayCallback(data),
					data
				});
			});
		}
	});

    return query.value === ''
        ? structuredPreferences
        : structuredPreferences.filter((sp) => sp.display.toLowerCase().includes(query.value.toLowerCase()));
});

const calculateSelectedPreference = () => {
    selectedPreference.value = props.item.preference.type
        ? props.item.preference.type.charAt(0) + '|' + props.item.preference.entity.id
        : false;
};

const saveChoice = (prefId) => {
	// They can double-click, and it should store the passed in value
	savePreference(props.item.id, prefId ?? selectedPreference.value);
	emit('close');
};

watch(() => props.item, () => calculateSelectedPreference());
</script>
