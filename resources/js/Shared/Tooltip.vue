<template>
    <div
        @mouseenter="updateBubbleState(true)"
        @mousemove="updateTooltip"
        @mouseleave="updateBubbleState(false)"
    >
        <Popover v-slot="{ open }">
            <PopoverButton
                ref="button"
                class="popover-button w-full focus:outline-none"
            >
                <slot name="hoverElement" />
            </PopoverButton>
            <div
                v-if="!invalid"
                v-show="open"
                ref="bubble"
                :class="tooltipBubbleClasses"
                :tooltip-shown="open"
                :style="{
                    [direction]: bubbleLeft,
                    top: bubbleTop,
                    transform: !props.trackMouse ? 'translate(0, -100%)' : '',
                }"
            >
                <PopoverPanel static>
                    <slot name="text" />
                </PopoverPanel>
            </div>
        </Popover>
    </div>
</template>

<script setup>
import { Popover, PopoverButton, PopoverPanel } from "@headlessui/vue";
import { ref } from "vue";
import { defaultString, stringProp, defaultFalse } from "@H/propDefaults";

const bubble = ref(null);
const bubbleLeft = ref("50%");
const bubbleTop = ref("0px");
const button = ref(null);

const props = defineProps({
    trackMouse: {
        type: [Object, Boolean],
        default: false,
    },
    tooltipBubbleClasses: defaultString,
    direction: stringProp("left"),
    invalid: defaultFalse,
});

const emits = defineEmits(["tooltipPositionUpdate"]);

const updateBubbleState = (shouldShow = false) => {
    if (props.invalid) {
        return;
    }

    if (bubble.value.getAttribute("tooltip-shown") === "false" && shouldShow) {
        button.value.$el.click();
    } else if (
        bubble.value.getAttribute("tooltip-shown") === "true" &&
        !shouldShow
    ) {
        button.value.$el.click();
    }
};

const updateTooltip = (event) => {
    if (props.invalid) {
        return;
    }

    if (props.trackMouse) {
        const { clientX, clientY, currentTarget } = event;
        const { top, right, bottom, left } =
            currentTarget.getBoundingClientRect();

        if (
            clientX >= left &&
            clientX <= right &&
            clientY >= top &&
            clientY <= bottom
        ) {
            updateBubbleState(true);

            const finalLeft = props.trackMouse.left ?? clientX - left;
            const finalTop = props.trackMouse.top ?? clientY - top;

            emits("tooltipPositionUpdate", { left: finalLeft, top: finalTop });

            bubbleLeft.value = props.trackMouse.left ?? `${finalLeft}px`;
            bubbleTop.value = props.trackMouse.top ?? `${finalTop}px`;
        } else {
            updateBubbleState(false);
        }
    }
};
</script>
