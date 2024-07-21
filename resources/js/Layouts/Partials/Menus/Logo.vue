<template>
    <div>
        <!-- Footer goes to original homepage; Header goes to Game's homepage -->
        <Link :href="route('home')">
            <div class="relative inline-flex w-11 items-center justify-center overflow-hidden lg:w-[50px]">
                <img src="/images/logos/logo.png" srcset="/images/logos/logo@2x.png 2x" alt="Crafting Ninja">
            </div>
        </Link>
    </div>
</template>

<script setup>
import { Link } from "@inertiajs/vue3";
</script>
