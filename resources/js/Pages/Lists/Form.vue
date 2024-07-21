<template>
    <Page title="List" :hero="false" :hero-flavor="isCreate ? 'Create' : 'Edit'">
        <div class="grid grid-cols-2 gap-4">
            <div
                class="flex flex-col gap-4 pr-8"
            >
                <div class="flex space-x-4">
                    <div class="flex-1">
                        <InputLabel for="name" value="List Name" />
                        <TextInput
                            id="name"
                            ref="nameInput"
                            v-model="form.name"
                            type="text"
                            class="mt-1 block w-full"
                            required
                            autocomplete="list-name"
                            autofocus
                        />
                        <InputError class="mt-2" :message="form.errors.name" />
                    </div>
                </div>
                <div>
                    <InputLabel for="description" value="List Description" />
                    <Textarea
                        id="description"
                        ref="descriptionInput"
                        v-model="form.description"
                        class="mt-1 block w-full"
                    />
                    <InputError class="mt-2" :message="form.errors.description" />
                </div>
                <div>
                    <label class="flex items-center">
                        <Checkbox v-model:checked="form.is_public" name="remember" />
                        <span class="ml-2 font-bold uppercase text-gray-900 dark:text-white">Public</span>
                        <span v-if="form.is_public" class="ml-2">
                            Everyone will be able to search and find your list. Please be kind.
                        </span>
                    </label>
                </div>
                <div class="flex justify-between mt-3">
                    <Button
                        type="subtle"
                        @click="cancel"
                    >
                        Cancel
                    </Button>

                    <Button
                        :icon="SaveIcon"
                        :thin="true"
                        @click="submit"
                        class="ml-auto"
                        :class="{ 'opacity-25': form.processing }"
                        :disabled="form.processing"
                    >
                        Save List
                    </Button>
                </div>
            </div>
            <div>
                <InputLabel value="Items" />
                <ul class="grid grid-cols-2 gap-x-8">
                    <template
                        v-for="entry in form.items"
                        :key="entry.item_id"
                    >
                        <li
                            v-if="loadedItems[entry.item_id]"
                            class="flex space-x-2 leading-[20px] hover:bg-accent/10 p-1 rounded"
                        >
                            <img
                                :src="loadedItems[entry.item_id].icon"
                                class="w-[20px] h-[20px] self-center"
                                :class="{
                                    'opacity-20': entry.delete
                                }"
                                alt="Icon for item"
                            />
                            <span
                                class="flex-1 truncate"
                                :class="{
                                    'line-through dark:text-gray-500': entry.delete
                                }"
                            >
                                {{ loadedItems[entry.item_id].name }}
                            </span>
                            <div class="group relative [&>.sub-menu]:hover:visible [&>.sub-menu]:hover:animate-popper-pop-in [&>.sub-menu]:hover:opacity-100">
                                <span
                                    :class="{
                                        'line-through dark:text-gray-600': entry.delete
                                    }"
                                >
                                    <span
                                        class="text-gray-600 mr-0.5"
                                    >x</span>{{ entry.quantity }}
                                </span>
                                <div class="sub-menu invisible absolute -top-1 -right-1 w-[150px] z-20 flex gap-x-3 bg-white dark:bg-gray-700 p-3 text-sm font-bold opacity-0 shadow-2xl transition-all">
                                    <TextInput
                                        type="number"
                                        v-model="entry.quantity"
                                        class="flex-1"
                                    />
                                    <button
                                        type="button"
                                        class=""
                                        :class="{
                                            'text-primary': !entry.delete
                                        }"
                                        @click="entry.delete = ! entry.delete"
                                    >
                                        <component
                                            :is="entry.delete ? RestoreIcon : DeleteIcon"
                                            class="w-6 h-6"
                                        />
                                    </button>
                                </div>
                            </div>
                        </li>
                    </template>
                </ul>
            </div>
        </div>
    </Page>
</template>

<script setup>
import Page from "@/Layouts/Page.vue";
import { onMounted, ref } from "vue";
import { router, useForm } from "@inertiajs/vue3";
import TextInput from "@/Components/Jetstream/TextInput.vue";
import Textarea from "@/Components/Jetstream/Textarea.vue";
import Checkbox from "@/Components/Jetstream/Checkbox.vue";
import InputError from "@/Components/Jetstream/InputError.vue";
import InputLabel from "@/Components/Jetstream/InputLabel.vue";
import SaveIcon from '~icons/mdi/content-save-plus';
import Button from "@S/Button.vue";
import DeleteIcon from '~icons/mdi/delete';
import RestoreIcon from '~icons/mdi/restore';

const props = defineProps({
    list: Object
})

const isCreate = route().current() !== 'lists.edit';

const form = useForm({
    name: props.list.name,
    description: props.list.description,
    is_public: props.list.is_public,
    items: props.list.items,
});

const cancel = () => isCreate ? router.visit(route('cart.index')) : router.visit(route('lists.show', props.list.sqid));

const submit = () => isCreate ? form.post(route('lists.store')) : form.put(route('lists.update', props.list.sqid));

const loadedItems = ref({});
const loading = ref(true);

onMounted(() => {
    const itemIDs = Object.values(props.list.items).map((entry) => entry.item_id);
    axios.post(route('api.items.many'), { items: itemIDs })
        .then((response) => {
            response.data.forEach((data) => (loadedItems.value[data.id] = data));
            loading.value = false;
        });
});
</script>
