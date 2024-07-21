<script setup>
import DeleteUserForm from '@/Pages/Profile/Partials/DeleteUserForm.vue';
import LogoutOtherBrowserSessionsForm from '@/Pages/Profile/Partials/LogoutOtherBrowserSessionsForm.vue';
import SectionBorder from '@/Components/Jetstream/SectionBorder.vue';
import TwoFactorAuthenticationForm from '@/Pages/Profile/Partials/TwoFactorAuthenticationForm.vue';
import UpdatePasswordForm from '@/Pages/Profile/Partials/UpdatePasswordForm.vue';
import UpdateProfileInformationForm from '@/Pages/Profile/Partials/UpdateProfileInformationForm.vue';
import SectionHead from "@S/SectionHead.vue";

defineProps({
    confirmsTwoFactorAuthentication: Boolean,
    sessions: Array,
});
</script>

<template>
	<SectionHead title="Profile" :center="true" />

	<div>
		<div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">
			<div v-if="$page.props.jetstream.canUpdateProfileInformation">
				<UpdateProfileInformationForm :user="$page.props.auth.user" />

				<SectionBorder />
			</div>

			<div v-if="$page.props.jetstream.canUpdatePassword">
				<UpdatePasswordForm class="mt-10 sm:mt-0" />
			</div>
		</div>
	</div>
</template>
