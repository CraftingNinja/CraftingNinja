<script setup>
import { ref } from 'vue';
import { useForm } from '@inertiajs/vue3';
import AuthenticationCard from '@/Components/Jetstream/AuthenticationCard.vue';
import InputError from '@/Components/Jetstream/InputError.vue';
import InputLabel from '@/Components/Jetstream/InputLabel.vue';
import PrimaryButton from '@/Components/Jetstream/PrimaryButton.vue';
import TextInput from '@/Components/Jetstream/TextInput.vue';
import SectionHead from "@S/SectionHead.vue";

const form = useForm({
    password: '',
});

const passwordInput = ref(null);

const submit = () => {
    form.post(route('password.confirm'), {
        onFinish: () => {
            form.reset();

            passwordInput.value.focus();
        },
    });
};
</script>

<template>
	<SectionHead title="Confirm Password" :center="true" />

    <AuthenticationCard>
        <div class="mb-4 text-sm text-gray-600 dark:text-gray-400">
            Please confirm your password before continuing.
        </div>

        <form @submit.prevent="submit">
            <div>
                <InputLabel for="password" value="Password" />
                <TextInput
                    id="password"
                    ref="passwordInput"
                    v-model="form.password"
                    type="password"
                    class="mt-1 block w-full"
                    required
                    autocomplete="current-password"
                    autofocus
                />
                <InputError class="mt-2" :message="form.errors.password" />
            </div>

            <div class="flex justify-end mt-4">
                <PrimaryButton class="ml-4" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                    Confirm
                </PrimaryButton>
            </div>
        </form>
    </AuthenticationCard>
</template>
