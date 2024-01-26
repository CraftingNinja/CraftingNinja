<template>
	<footer id="site-footer">
		<div class="bg-gray-50 py-12 dark:bg-gray-900 sm:py-16 md:py-20 lg:py-14">
			<div class="mx-auto max-w-[1440px] px-5">
				<div class="grid grid-cols-1 items-baseline justify-center gap-y-10 lg:grid-cols-[20%_1fr_20%]">
					<div class="justify-self-center lg:justify-self-start">
						<img src="/images/logos/logo.png" srcset="/images/logos/logo@2x.png 2x" alt="Crafting Ninja" class="w-10 inline-block">
					</div>

					<nav>
						<ul class="flex flex-wrap justify-center gap-x-4 text-base font-bold uppercase tracking-tight sm:gap-x-6 lg:gap-x-7 xl:gap-x-10">
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
						</ul>
					</nav>

					<ul class="flex flex-wrap gap-6 justify-self-center lg:gap-4 lg:justify-self-end xl:gap-6">
						<li>
							<a class="text-gray-900 transition-colors hover:text-primary dark:text-white dark:hover:text-primary" href="https://patreon.com/ffxivcrafting" title="Patreon" target="_blank">
								<svg class="h-4 w-4 inline-block mr-3" fill="currentColor">
									<use xlink:href="/images/social-icons.svg#patreon"></use>
								</svg>
								Support CN on Patreon!
							</a>
						</li>
					</ul>
				</div>
			</div>
		</div>
	</footer>
</template>

<script setup>
import { Link } from '@inertiajs/vue3';
</script>
