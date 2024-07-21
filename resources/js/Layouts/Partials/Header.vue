<template>
	<div class="header-wrapper text-white">
		<header id="site-header" class="text-white">
			<div class="mx-auto max-w-[1440px] px-5">
				<nav class="grid grid-cols-[100px_1fr] lg:grid-cols-[21%_1fr_21%] min-h-[64px] gap-x-6 lg:gap-x-11 py-1 lg:min-h-[90px] lg:py-4">

					<!-- Social Links -->
					<ul class="flex-wrap gap-3.5 lg:gap-4 xl:gap-6 self-center hidden lg:flex">
						<li class="relative [&>.sub-menu]:lg:hover:visible [&>.sub-menu]:hover:animate-popper-pop-in [&>.sub-menu]:lg:hover:opacity-100">
							<a class="text-gray-900 transition-colors hover:text-primary dark:text-white dark:hover:text-primary" href="https://patreon.com/ffxivcrafting" title="Patreon" target="_blank">
                                <PatreonIcon class="w-4 h-4" />
							</a>

                            <div
                                class="sub-menu invisible absolute left-full z-20 -mr-9 w-64 flex origin-top-right flex-col bg-white dark:bg-gray-700 py-3 text-sm font-bold opacity-0 shadow-2xl transition-all uppercase"
                            >
                                <span class="px-5.5">
                                    Please consider supporting Crafting Ninja on Patreon!
                                </span>
                            </div>
						</li>
					</ul>
					<!-- Social Links / End -->

					<!-- Navigation & Logo -->
					<div class="grid lg:grid-cols-[1fr_110px_1fr] xl:grid-cols-[1fr_150px_1fr] items-center">
                        <component
                            :is="game ? GameMenu : NinjaMenu"
                        />
					</div>
					<!-- Navigation & Logo / End -->

					<!-- Header Controls -->
					<div class="flex ml-auto items-center">
						<UserMenu />

						<button
							class="-mr-2 ml-2 inline-flex py-4 px-2 sm:px-3 lg:hidden xl:px-4"
							:class="{
								'active': mobileMenuOpen
							}"
							@click="mobileMenuOpen = !mobileMenuOpen"
						>
							<svg
								role="img"
								class="h-6 w-6 fill-gray-900 dark:fill-white"
								:class="{
									'hidden': mobileMenuOpen
								}"
							>
								<use xlink:href="/images/sprite.svg#menu"></use>
							</svg>
							<svg
								role="img"
								class="h-6 w-6 fill-gray-900 dark:fill-white"
								:class="{
									'hidden': !mobileMenuOpen
								}"
							>
								<use xlink:href="/images/sprite.svg#menu-close"></use>
							</svg>
						</button>
					</div>
				</nav>
			</div>
		</header>
	</div>

	<div
		class="p-t-[64px] fixed left-0 top-[64px] z-50 block h-[calc(100dvh-64px)] w-full translate-x-full overflow-auto bg-white dark:bg-gray-700 py-5 text-primary dark:text-white transition-transform duration-300 lg:hidden"
		:class="{
			'translate-x-full': !mobileMenuOpen,
			'translate-x-0': mobileMenuOpen,
		}"
	>
		<div class="container">
			<ul class="flex flex-col font-bold">
                <component
                    :is="game ? GameMobileMenu : NinjaMobileMenu"
                />
			</ul>

			<ul class="flex flex-wrap gap-5 pt-8">
				<li>
					<a class="text-primary transition-colors hover:text-primary dark:text-white dark:hover:text-primary" href="https://patreon.com/ffxivcrafting" title="Patreon">
						<svg class="h-4 w-4" fill="currentColor">
							<use xlink:href="/images/social-icons.svg#patreon"></use>
						</svg>
					</a>
				</li>
			</ul>
		</div>
	</div>
</template>

<script setup>
import { ref } from "vue";
import { usePage } from '@inertiajs/vue3';
import GameMenu from "@/Layouts/Partials/Menus/GameMenu.vue";
import NinjaMenu from "@/Layouts/Partials/Menus/NinjaMenu.vue";
import GameMobileMenu from "@/Layouts/Partials/Menus/GameMobileMenu.vue";
import NinjaMobileMenu from "@/Layouts/Partials/Menus/NinjaMobileMenu.vue";
import UserMenu from "@/Layouts/Partials/Menus/UserMenu.vue";
import { PatreonIcon } from "@H/iconLibrary.js";

const game = usePage().props.game;

const mobileMenuOpen = ref(false);
</script>
