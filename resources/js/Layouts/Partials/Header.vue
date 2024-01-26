<template>
	<div class="header-wrapper text-white">
		<header id="site-header" class="text-white">
			<div class="mx-auto max-w-[1440px] px-5">
				<nav class="grid grid-cols-[100px_1fr] lg:grid-cols-[21%_1fr_21%] min-h-[64px] gap-x-6 lg:gap-x-11 py-1 lg:min-h-[90px] lg:py-4">

					<!-- Social Links -->
					<ul class="flex-wrap gap-3.5 lg:gap-4 xl:gap-6 self-center hidden lg:flex">
						<li>
							<a class="text-gray-900 transition-colors hover:text-primary dark:text-white dark:hover:text-primary" href="https://patreon.com/ffxivcrafting" title="Patreon" target="_blank">
                                <!-- TODO 1 - Replace with iconify -->
								<svg class="w-3.5 xl:w-4 aspect-square" fill="currentColor">
									<use xlink:href="/images/social-icons.svg#patreon"></use>
								</svg>
							</a>
						</li>
					</ul>
					<!-- Social Links / End -->

					<!-- Navigation & Logo -->
					<div class="grid lg:grid-cols-[1fr_110px_1fr] xl:grid-cols-[1fr_150px_1fr] items-center">
						<!-- Navigation (Desktop) -->
						<ul class="hidden lg:gap-x-5 xl:gap-x-8 text-gray-900 dark:text-white lg:text-sm xl:text-base font-bold uppercase tracking-tight lg:flex min-[1260px]:gap-x-10 justify-self-end">
							<li class="">
								<HeaderNavLink route-name="library.index">
									Library
								</HeaderNavLink>
							</li>
							<li class="">
								<HeaderNavLink route-name="lists.index">
									Lists
								</HeaderNavLink>
							</li>
						</ul>
						<!-- Navigation (Desktop) / End -->

						<!-- Logo -->
						<div class="lg:mx-auto">
							<Link :href="route('home')">
								<div class="relative inline-flex w-11 items-center justify-center overflow-hidden lg:w-[50px]">
									<img src="/images/logos/logo.png" srcset="/images/logos/logo@2x.png 2x" alt="Crafting Ninja">
								</div>
							</Link>
						</div>
						<!-- Logo / End -->

						<!-- Navigation (Desktop) -->
						<ul class="hidden lg:gap-x-5 xl:gap-x-8 text-gray-900 dark:text-white lg:text-sm xl:text-base font-bold uppercase tracking-tight lg:flex min-[1260px]:gap-x-10">
							<li class="relative pr-5 [&>.sub-menu]:hover:visible [&>.sub-menu]:hover:animate-popper-pop-in [&>.sub-menu]:hover:opacity-100">
								<HeaderNavLink>
									Features
                                    <!-- TODO 1 - Replace with iconify -->
									<svg role="img" class="h-2 w-2 rotate-90 fill-gray-900 dark:fill-white absolute -right-3 top-1/2 -translate-y-1/2">
										<use xlink:href="/images/sprite.svg#arrow-right"></use>
									</svg>
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
						<!-- Navigation (Desktop) / End -->
					</div>
					<!-- Navigation & Logo / End -->

					<!-- Header Controls -->
					<div class="flex ml-auto items-center">

						<!--<div class="flex items-center py-4 px-1 sm:px-2">-->
						<!--  <label for="theme-toggle" class="relative inline-flex cursor-pointer items-center">-->
						<!--    <input type="checkbox" value="" id="theme-toggle" class="peer sr-only">-->
						<!--    <span class="relative z-10 block h-6 w-11 rounded-full border-2 border-gray-900 after:absolute after:top-0.5 after:left-0.5 after:h-4 after:w-4 after:rounded-full after:bg-gray-900 after:transition-transform peer-checked:after:translate-x-[20px] dark:border-white dark:after:bg-white"></span>-->
						<!--    <svg role="img" class="pointer-events-none absolute left-[5px] top-1 h-4 w-4 stroke-gray-900 dark:stroke-white">-->
						<!--      <use xlink:href="assets/img/yt1/sprite.svg#sun"></use>-->
						<!--    </svg>-->
						<!--    <svg role="img" class="pointer-events-none absolute right-[5px] top-1 h-4 w-4 stroke-gray-900 dark:stroke-white">-->
						<!--      <use xlink:href="assets/img/yt1/sprite.svg#moon"></use>-->
						<!--    </svg>-->
						<!--  </label>-->
						<!--</div>-->

						<div class="relative [&>.sub-menu]:lg:hover:visible [&>.sub-menu]:hover:animate-popper-pop-in [&>.sub-menu]:lg:hover:opacity-100">
							<Link
								v-if="!isLoggedIn"
								:href="route('login')"
								class="block py-4 px-2 xl:px-3"
							>
								<svg role="img" class="h-6 w-6 fill-gray-900 dark:fill-white">
									<use xlink:href="/images/sprite.svg#user"></use>
								</svg>
							</Link>
							<Link
								v-else
								:href="route('profile.show')"
								class="block py-4 px-2 xl:px-3"
							>
								<svg role="img" class="h-6 w-6 fill-gray-900 dark:fill-white">
									<use xlink:href="/images/sprite.svg#user"></use>
								</svg>
							</Link>

							<ul
								v-if="isLoggedIn"
								class="sub-menu invisible absolute right-full z-20 -mr-9 flex w-40 origin-top-right flex-col bg-white dark:bg-gray-700 py-3 text-sm font-bold opacity-0 shadow-2xl transition-all uppercase"
							>
								<!--<li class="px-5.5">-->
								<!--	<a-->
								<!--		href="_str2-account-orders.html"-->
								<!--		class="flex flex-row-reverse items-center justify-between py-1.5 text-gray-900 dark:text-white transition-colors hover:text-primary dark:hover:text-primary"-->
								<!--	>-->
								<!--		Orders-->
								<!--	</a>-->
								<!--</li>-->
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

						<!--<div class="relative [&>.sub-menu]:lg:hover:visible [&>.sub-menu]:hover:animate-popper-pop-in [&>.sub-menu]:lg:hover:opacity-100 lg:-mr-2">-->
							<!--<a class="relative block py-4 px-1 sm:px-2" href="_str2-cart.html">-->
							<!--	<svg role="img" class="h-6 w-6 fill-gray-900 dark:fill-white">-->
							<!--		<use xlink:href="/images/sprite.svg#shopping-basket"></use>-->
							<!--	</svg>-->
							<!--	<span class="absolute top-1 -right-2 text-3xs font-bold leading-none w-4.5 h-4.5 rounded-full bg-primary inline-flex justify-center items-center">2</span>-->
							<!--</a>-->
						<!--</div>-->

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

	<!-- Mobile Menu -->
	<!-- TODO 1 - Mobile Menu -->
	<div
		class="p-t-[64px] fixed left-0 top-[64px] z-50 block h-[calc(100dvh-64px)] w-full translate-x-full overflow-auto bg-white dark:bg-gray-700 py-5 text-primary dark:text-white transition-transform duration-300 lg:hidden"
		:class="{
			'translate-x-full': !mobileMenuOpen,
			'translate-x-0': mobileMenuOpen,
		}"
	>
		<div class="container">
			<!-- Navigation (Mobile) -->
			<ul class="flex flex-col font-bold">
				<li class="flex flex-wrap items-center gap-x-4 border-b border-b-gray-200/50 dark:border-b-gray-200/10">
					<a class="flex-grow gap-x-1 py-4 uppercase leading-normal text-gray-900 transition-colors hover:text-primary dark:text-white dark:hover:text-primary" href="_str2-index.html">
						Home
					</a>


				</li>
				<li class="flex flex-wrap items-center gap-x-4 border-b border-b-gray-200/50 dark:border-b-gray-200/10">
					<a class="flex-grow gap-x-1 py-4 uppercase leading-normal text-gray-900 transition-colors hover:text-primary dark:text-white dark:hover:text-primary" href="_str2-stream.html">
						Stream
					</a>


				</li>
				<li class="flex flex-wrap items-center gap-x-4 border-b border-b-gray-200/50 dark:border-b-gray-200/10">
					<a class="flex-grow gap-x-1 py-4 uppercase leading-normal text-gray-900 transition-colors hover:text-primary dark:text-white dark:hover:text-primary" href="_str2-affiliates.html">
						Affiliates
					</a>


				</li>
				<li class="flex flex-wrap items-center gap-x-4 border-b border-b-gray-200/50 dark:border-b-gray-200/10">
					<a class="flex-grow gap-x-1 py-4 uppercase leading-normal text-gray-900 transition-colors hover:text-primary dark:text-white dark:hover:text-primary" href="_str2-shop.html">
						Shop
					</a>

					<button class="js-mobile-submenu-toggle ml-auto inline-flex h-7 w-7 items-center justify-center transition-transform">
						<svg role="img" class="sub-menu-toggle h-2 w-2 rotate-90 fill-primary dark:fill-white">
							<use xlink:href="/images/sprite.svg#arrow-right"></use>
						</svg>
					</button>

					<ul class="flex max-h-0 w-full flex-col overflow-hidden pl-4 text-sm transition-all duration-300 [&>li:last-child]:pb-4">
						<li class="flex flex-wrap items-center gap-x-4">

							<a class="flex-grow gap-x-1 py-2 text-gray-900 transition-colors hover:text-primary dark:text-white dark:hover:text-primary" href="_str2-shop.html">
								Shop Page
							</a>

						</li>
						<li class="flex flex-wrap items-center gap-x-4">

							<a class="flex-grow gap-x-1 py-2 text-gray-900 transition-colors hover:text-primary dark:text-white dark:hover:text-primary" href="_str2-single-product.html">
								Single Product
							</a>

						</li>
						<li class="flex flex-wrap items-center gap-x-4">

							<a class="flex-grow gap-x-1 py-2 text-gray-900 transition-colors hover:text-primary dark:text-white dark:hover:text-primary" href="_str2-cart.html">
								Cart
							</a>

						</li>
						<li class="flex flex-wrap items-center gap-x-4">

							<a class="flex-grow gap-x-1 py-2 text-gray-900 transition-colors hover:text-primary dark:text-white dark:hover:text-primary" href="_str2-checkout.html">
								Checkout
							</a>

						</li>
						<li class="flex flex-wrap items-center gap-x-4">

							<button class="js-vv-modal__open-btn-login-register flex-grow gap-x-1 py-2 text-left text-gray-900 transition-colors hover:text-primary dark:text-white dark:hover:text-primary" data-id="login-register">
								Login/Register
							</button>

						</li>
						<li class="flex flex-wrap items-center gap-x-4">

							<a class="flex-grow gap-x-1 py-2 text-gray-900 transition-colors hover:text-primary dark:text-white dark:hover:text-primary" href="_str2-account.html">
								Account
							</a>

						</li>
					</ul>

				</li>
				<li class="flex flex-wrap items-center gap-x-4 border-b border-b-gray-200/50 dark:border-b-gray-200/10">
					<a class="flex-grow gap-x-1 py-4 uppercase leading-normal text-gray-900 transition-colors hover:text-primary dark:text-white dark:hover:text-primary" href="_str2-contact.html">
						Contact
					</a>


				</li>
				<li class="flex flex-wrap items-center gap-x-4 border-b border-b-gray-200/50 dark:border-b-gray-200/10">
					<a class="flex-grow gap-x-1 py-4 uppercase leading-normal text-gray-900 transition-colors hover:text-primary dark:text-white dark:hover:text-primary" href="#.html">
						Donate
					</a>


				</li>
			</ul>
			<!-- Navigation (Mobile) / End -->

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
	<!-- Mobile Menu / End -->
</template>

<script setup>
import { ref, watch } from "vue";
import { Link, usePage } from '@inertiajs/vue3';
import HeaderNavLink from "@/Layouts/Partials/HeaderNavLink.vue";
import HeaderSubMenuNavLink from "@/Layouts/Partials/HeaderSubMenuNavLink.vue";
import Counter from "@/Shared/Counter.vue";
import ListComposable from "@/Shared/List/composable.js";

const { totalQuantity } = ListComposable();

const mobileMenuOpen = ref(false);
const isLoggedIn = ref(usePage().props.auth.user !== null);

watch(() => usePage().props.auth.user, () => (isLoggedIn.value = usePage().props.auth.user !== null));
</script>
