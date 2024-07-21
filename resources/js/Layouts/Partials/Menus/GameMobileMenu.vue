<template>
    <!-- TODO 1 -->
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
</template>

<script setup>

</script>
