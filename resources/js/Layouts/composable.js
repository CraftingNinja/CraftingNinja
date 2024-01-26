import { readonly, ref } from "vue";
import { v4 as uuidv4 } from "uuid";

const activeRouteName = ref(false);

const toasts = ref([]);
export const TOAST_TIMEOUT_DURATION_DEFAULT = 3500;

const addToast = (toast, type = "message") => {
    if (typeof toast === "string") {
        toast = { title: toast, type };
    }

    toast.id = uuidv4();
    toast.show = true;
    toast.type ??= type;

    // Add it to the toasts array
    toasts.value.push(toast);

    return toast;
};

const removeToast = (toast) => {
    // `.slice`-ing out of `toasts` is a bad idea.
    // Changing it triggers an unmount on all the components, which triggers the removeToast function
    // and, in cases of multiple notifications, everything disappears at once.

    // Can't modify `toast` directly; it's readonly
    const original = toasts.value.find((item) => item.id === toast.id);

    if (original) {
        original.show = false;
    }

    // `toasts` will just continue accumulating values while the SPA is active.
    // However, we can't touch the array while anything is showing.
    if (toasts.value.filter((item) => item.show === true).length === 0) {
        toasts.value = [];
    }
};

export default function () {
    return {
        activeRouteName,
        toasts: readonly(toasts),
        addToast,
        removeToast,
    };
}
