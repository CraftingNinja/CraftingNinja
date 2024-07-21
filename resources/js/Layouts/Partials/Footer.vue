<template>
	<footer id="site-footer">
		<div class="bg-gray-50 py-12 dark:bg-gray-900 sm:py-16 md:py-20 lg:py-14">
			<div class="mx-auto max-w-[1440px] px-5">
				<div class="grid grid-cols-1 items-baseline justify-center gap-y-10 lg:grid-cols-[20%_1fr_20%]">
					<div class="justify-self-center lg:justify-self-start">
                        <!-- Footer goes to original homepage; Header goes to Game's homepage -->
                        <!-- Hard anchor to allow subdomain change. -->
                        <a :href="rootUrl">
						    <img
                                src="/images/logos/logo.png"
                                srcset="/images/logos/logo@2x.png 2x"
                                alt="Crafting Ninja"
                                class="w-10 inline-block"
                            />
                        </a>
					</div>

					<nav>
						<ul class="flex flex-wrap justify-center gap-x-4 text-base font-bold uppercase tracking-tight sm:gap-x-6 lg:gap-x-7 xl:gap-x-10">
                            <template v-if="game">
                                <li>
                                    <Link
                                        :href="route('home')"
                                        class="text-gray-900 transition-colors hover:text-primary dark:text-white dark:hover:text-primary"
                                    >
                                        Home
                                    </Link>
                                </li>
                                <li>
                                    <Link
                                        :href="route('library.index')"
                                        class="text-gray-900 transition-colors hover:text-primary dark:text-white dark:hover:text-primary"
                                    >
                                        Library
                                    </Link>
                                </li>
                                <li>
                                    <Link
                                        :href="route('lists.index')"
                                        class="text-gray-900 transition-colors hover:text-primary dark:text-white dark:hover:text-primary"
                                    >
                                        Lists
                                    </Link>
                                </li>
                            </template>
						</ul>
					</nav>

					<ul class="flex flex-wrap gap-6 justify-self-center lg:gap-4 lg:justify-self-end xl:gap-6">
						<li>
							<a
                                class="text-gray-900 transition-colors hover:text-primary dark:text-white dark:hover:text-primary"
                                href="https://patreon.com/ffxivcrafting"
                                title="Patreon"
                                target="_blank"
                            >
                                <PatreonIcon class="w-4 h-4 inline mr-1.5" />
								Support CN on Patreon!
							</a>
						</li>
					</ul>
				</div>
			</div>
		</div>

        <div
            v-if="game?.meta?.legal"
            class="bg-primary py-8"
        >
            <div class="mx-auto max-w-[1440px] px-5">
                <div class="flex flex-col flex-wrap items-center justify-between gap-y-2 text-center text-sm font-bold leading-6 tracking-tight text-white md:flex-row">
                    <div class="flex-1">
                        {{ game.meta.legal }}
                    </div>
                    <!--<nav>-->
                    <!--    <ul class="flex flex-wrap gap-x-6 [&>li:not(:first-child)]:before:absolute [&>li:not(:first-child)]:before:top-1/2 [&>li:not(:first-child)]:before:-left-3 [&>li:not(:first-child)]:before:h-2.5 [&>li:not(:first-child)]:before:w-[1px] [&>li:not(:first-child)]:before:-translate-y-1/2 [&>li:not(:first-child)]:before:bg-white [&>li]:relative">-->
                    <!--        <li>-->
                    <!--            <a class="vv-link-underline" href="#">Terms of Service</a>-->
                    <!--        </li>-->
                    <!--        <li>-->
                    <!--            <a class="vv-link-underline" href="#">Privacy Policy</a>-->
                    <!--        </li>-->
                    <!--    </ul>-->
                    <!--</nav>-->
                </div>
            </div>
        </div>
	</footer>
</template>

<script setup>
import { Link, usePage } from '@inertiajs/vue3';
import { PatreonIcon } from "@H/iconLibrary.js";

const game = usePage().props.game;
const rootUrl = usePage().props.rootUrl;
</script>
