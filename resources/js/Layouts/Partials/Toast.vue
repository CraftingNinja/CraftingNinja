<template>
    <transition
        enter-active-class="transform ease-out duration-300 transition"
        enter-from-class="translate-y-2 opacity-0 sm:translate-y-0 sm:translate-x-2"
        enter-to-class="translate-y-0 opacity-100 sm:translate-x-0"
        leave-active-class="transition ease-in duration-100"
        leave-from-class="opacity-100"
        leave-to-class="opacity-0"
    >
        <div
            v-if="toast.show"
            class="pointer-events-auto w-full max-w-sm shrink-0 overflow-hidden rounded bg-white dark:bg-gray-675 shadow-lg ring-1 ring-black ring-opacity-5"
        >
            <div
                class="relative border-l-4"
                :class="{
                    'border-gray-400': toast.type === 'message',
                    'border-green-400': toast.type === 'success',
                    'border-yellow-400': [
                        'warning',
                        'locked',
                        'question',
                    ].includes(toast.type),
                    'border-blue-400': toast.type === 'info',
                    'border-red-400': toast.type === 'error',
                }"
            >
                <div
                    v-if="!toast.forever"
                    class="top absolute h-0.5 w-full opacity-30 transition-all ease-linear"
                    :class="{
                        'bg-gray-400': toast.type === 'message',
                        'bg-green-400': toast.type === 'success',
                        'bg-yellow-400': [
                            'warning',
                            'locked',
                            'question',
                        ].includes(toast.type),
                        'bg-blue-400': toast.type === 'info',
                        'bg-red-400': toast.type === 'error',
                    }"
                    :style="{
                        width: (triggerWidthAnimation ? 0 : 100) + '%',
                        'transition-duration': duration + 'ms',
                    }"
                >
                    &nbsp;
                </div>
                <div class="flex items-start p-4">
                    <component
                        :is="availableIcons[toast.type]"
                        v-if="availableIcons[toast.type]"
                        class="h-6 w-6"
                        :class="{
                            'text-green-400': toast.type === 'success',
                            'text-yellow-400': [
                                'warning',
                                'locked',
                                'question',
                            ].includes(toast.type),
                            'text-blue-400': toast.type === 'info',
                            'text-red-400': toast.type === 'error',
                        }"
                        aria-hidden="true"
                    />
                    <div class="ml-3 flex-1 pt-0.5">
                        <p
                            v-if="toast.title"
                            class="text-sm font-medium text-gray-900 dark:text-white"
                            :class="{
                                'line-clamp-1': toast.clamp === true,
                            }"
                        >
                            {{ toast.title }}
                        </p>
                        <p
                            v-if="toast.message"
                            class="mt-1 text-sm text-gray-500 dark:text-gray-200"
                            :class="{
                                'line-clamp-1': toast.clamp === true,
                            }"
                        >
                            {{ toast.message }}
                        </p>
                        <div v-if="toast.action" class="mt-4 flex">
                            <button
                                type="button"
                                class="rounded-md bg-white text-sm font-medium text-indigo-600 hover:text-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
                                @click="handleAction(toast, toast.action)"
                            >
                                {{ toast.action.text || "Okay" }}
                            </button>
                        </div>
                        <div v-if="toast.buttons" class="mt-4 flex">
                            <button
                                type="button"
                                class="inline-flex items-center rounded-md border border-transparent bg-indigo-600 px-3 py-2 text-sm font-medium leading-4 text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
                                @click="handleAction(toast, toast.buttons.yes)"
                            >
                                {{ toast.buttons.yes.text || "Accept" }}
                            </button>
                            <button
                                type="button"
                                class="ml-3 inline-flex items-center rounded-md border border-gray-300 bg-white px-3 py-2 text-sm font-medium leading-4 text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
                                @click="close()"
                            >
                                {{ toast.buttons.no.text || "Decline" }}
                            </button>
                        </div>
                        <div v-if="toast.links" class="mt-3 flex space-x-7">
                            <button
                                type="button"
                                class="rounded-md bg-white text-sm font-medium text-indigo-600 hover:text-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
                                @click="handleAction(toast, toast.links.yes)"
                            >
                                {{ toast.links.yes.text || "Undo" }}
                            </button>
                            <button
                                type="button"
                                class="rounded-md bg-white text-sm font-medium text-gray-700 hover:text-gray-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
                                @click="close()"
                            >
                                {{ toast.links.no.text || "Dismiss" }}
                            </button>
                        </div>
                    </div>
                    <div class="ml-4 flex flex-shrink-0">
                        <button
                            type="button"
                            class="inline-flex rounded-md text-gray-400 dark:text-primary hover:text-gray-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
                            @click="close()"
                        >
                            <span class="sr-only">Close</span>
                            <XCircleIcon class="h-5 w-5" aria-hidden="true" />
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </transition>
</template>

<script setup>
import { onUnmounted, ref } from "vue";
import { router } from "@inertiajs/vue3";
import { XCircleIcon } from "@heroicons/vue/24/solid";
import {
    CheckCircleIcon,
    ExclamationTriangleIcon,
    ExclamationCircleIcon,
    InformationCircleIcon,
    LockClosedIcon,
    QuestionMarkCircleIcon,
} from "@heroicons/vue/24/outline";
import {
    default as LayoutComposable,
    TOAST_TIMEOUT_DURATION_DEFAULT,
} from "@/Layouts/composable.js";

const { removeToast } = LayoutComposable();

const availableIcons = {
    success: CheckCircleIcon,
    warning: ExclamationTriangleIcon,
    error: ExclamationCircleIcon,
    info: InformationCircleIcon,
    locked: LockClosedIcon,
    question: QuestionMarkCircleIcon,
};

const props = defineProps({
    toast: Object,
});

// Ensure we have a timeout duration
const duration = props.toast.duration || TOAST_TIMEOUT_DURATION_DEFAULT;
const triggerWidthAnimation = ref(false);
let timeoutHandler = null;

const close = () => {
    // If this is manually closed, we don't need the timout anymore
    clearTimeout(timeoutHandler);

    // Modifies the toast.show value for hiding as well
    removeToast(props.toast);

    if (props.toast.buttons?.no?.callback) {
        props.toast.buttons.no.callback(props.toast);
    }

    if (props.toast.links?.no?.callback) {
        props.toast.links.no.callback(props.toast);
    }
};

const handleAction = (toast, scope) => {
    // Any "Yes" action will also close the toast
    close();

    // Scope being "toast.action" or "toast.links.yes", etc
    // These sub-objects likely have a ".text" value and a ".route" or ".callback()" value
    // Handle either.

    if (scope.route !== undefined) {
        router.visit(scope.route);
    }

    if (scope.callback !== undefined) {
        scope.callback(toast);
    }
};

if (props.toast.forever !== true) {
    // Allow the CSS animation to happen by immediately changing the width to 0.
    // The animation duration matches our hidden timeout.
    setTimeout(() => {
        triggerWidthAnimation.value = true;
    }, 1);

    timeoutHandler = setTimeout(close, duration);

    // When you switch pages, we need to remove the toast, or it will show up again
    // But keep it around if it's "forever"; they'd have to refresh the page entirely to get rid of it
    onUnmounted(close);
}
</script>
