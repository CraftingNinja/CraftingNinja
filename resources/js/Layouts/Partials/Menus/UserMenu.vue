<template>
    <div class="relative [&>.sub-menu]:lg:hover:visible [&>.sub-menu]:hover:animate-popper-pop-in [&>.sub-menu]:lg:hover:opacity-100">
        <Link
            :href="!user ? route('login') : route('profile.show')"
            class="block py-4 px-2 xl:px-3"
        >
            <AccountIcon
                class="w-8 h-8"
                :class="{
                    'opacity-50 hover:opacity-100': !user,
                }"
            />
        </Link>

        <ul
            v-if="user"
            class="sub-menu invisible absolute right-full z-20 -mr-9 flex w-40 origin-top-right flex-col bg-white dark:bg-gray-700 py-3 text-sm font-bold opacity-0 shadow-2xl transition-all uppercase"
        >
            <li class="px-5.5">
                <span
                    class="flex flex-row-reverse items-center justify-between pt-1.5 pb-3 text-primary"
                >
                    Hello {{ user.name }}!
                </span>
            </li>
            <li class="px-5.5">
                <Link
                    :href="route('profile.show')"
                    class="flex flex-row-reverse items-center justify-between py-1.5 text-gray-900 dark:text-white transition-colors hover:text-primary dark:hover:text-primary"
                >
                    Profile
                </Link>
            </li>
            <li class="px-5.5">
                <Link
                    :href="route('logout')"
                    method="post"
                    as="button"
                    class="flex flex-row-reverse items-center justify-between py-1.5 text-gray-900 dark:text-white transition-colors hover:text-primary dark:hover:text-primary !uppercase !float-right"
                >
                    Logout
                </Link>
            </li>
        </ul>
    </div>
</template>

<script setup>
import { computed } from "vue";
import { Link, usePage } from '@inertiajs/vue3';
import { AccountIcon } from "@H/iconLibrary.js";

const game = usePage().props.game;
const user = computed(() => usePage().props.auth.user);
</script>
