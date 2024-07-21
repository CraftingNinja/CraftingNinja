<template>
    <ul class="hidden lg:gap-x-5 xl:gap-x-8 text-gray-900 dark:text-white lg:text-sm xl:text-base font-bold uppercase tracking-tight lg:flex min-[1260px]:gap-x-10 justify-self-end">
        <li>
            <HeaderNavLink route-name="library.index">
                Library
            </HeaderNavLink>
        </li>
        <li>
            <HeaderNavLink route-name="lists.index">
                Lists
            </HeaderNavLink>
        </li>
    </ul>

    <Logo class="lg:mx-auto" />

    <ul class="hidden lg:gap-x-5 xl:gap-x-8 text-gray-900 dark:text-white lg:text-sm xl:text-base font-bold uppercase tracking-tight lg:flex min-[1260px]:gap-x-10">
        <li class="relative pr-5 [&>.sub-menu]:hover:visible [&>.sub-menu]:hover:animate-popper-pop-in [&>.sub-menu]:hover:opacity-100">
            <HeaderNavLink class="items-center">
                Features
                <!-- absolute prevents underline -->
                <ChevronDownIcon class="absolute ml-.5 h-4.5 w-4.5 inline -right-5" />
            </HeaderNavLink>
            <ul class="sub-menu invisible absolute z-20 flex w-40 flex-col bg-white dark:bg-gray-700 py-2.5 text-sm font-bold opacity-0 shadow-2xl transition-all">
                <!--<li class="px-5.5">-->
                <!--	<HeaderSubMenuNavLink route-name="crafting.index">-->
                <!--		Crafting-->
                <!--	</HeaderSubMenuNavLink>-->
                <!--</li>-->
                <li class="px-5.5">
                    <HeaderSubMenuNavLink route-name="equipment.index">
                        Equipment
                    </HeaderSubMenuNavLink>
                </li>
                <li class="px-5.5">
                    <HeaderSubMenuNavLink route-name="leves.index">
                        Leves
                    </HeaderSubMenuNavLink>
                </li>
                <li class="px-5.5">
                    <HeaderSubMenuNavLink route-name="food.index">
                        Food
                    </HeaderSubMenuNavLink>
                </li>
                <li class="px-5.5">
                    <HeaderSubMenuNavLink route-name="hunting.index">
                        Hunting Log
                    </HeaderSubMenuNavLink>
                </li>
            </ul>
        </li>
        <li>
            <HeaderNavLink route-name="cart.index" id="your-list">
                Your List
                <Counter :number="totalQuantity" class="absolute top-1 -right-5 bg-primary text-white" />
            </HeaderNavLink>
        </li>
    </ul>
</template>

<script setup>
import { usePage } from '@inertiajs/vue3';
import HeaderNavLink from "@/Layouts/Partials/HeaderNavLink.vue";
import HeaderSubMenuNavLink from "@/Layouts/Partials/HeaderSubMenuNavLink.vue";
import Counter from "@S/Counter.vue";
import ListComposable from "@S/List/composable.js";
import Logo from "@/Layouts/Partials/Menus/Logo.vue";
import ChevronDownIcon from "~icons/mdi/chevron-down";

const { totalQuantity } = ListComposable();

const game = usePage().props.game;
</script>
