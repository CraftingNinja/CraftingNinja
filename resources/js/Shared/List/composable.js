// Cart/List composable
import { computed } from "vue";
import { useStorage } from "@H/useStorage.js";
import { gameAsset } from "@H/assets.js";

const activeList = useStorage('activeList', []);

// import AppComposable from "@/Layouts/composable.js";
// const { addToast } = AppComposable();

const animateAdd = (src, element) => {
    if ( ! src || ! element) {
        return;
    }

    const animationMs = 1000;

    const currentScroll = document.documentElement.scrollTop || document.body.scrollTop;
    const { top, left } = element.getBoundingClientRect();
    const imgEl = document.createElement('img');
    imgEl.src = gameAsset(src);
    imgEl.style.transition = `all ${animationMs}ms`;
    imgEl.style.position = 'absolute';
    imgEl.style.top = `${top + currentScroll - 40}px`;
    imgEl.style.left = `${left}px`;
    imgEl.style.height = '40px';
    imgEl.style.width = '40px';

    document.getElementById('body').appendChild(imgEl);

    const listEl = document.getElementById('your-list');
    const { top: listTop, right: listRight } = listEl.getBoundingClientRect();

    // Wait a tick
    setTimeout(() => {
        imgEl.style.top = `${listTop + 10}px`;
        imgEl.style.left = `${listRight}px`;
        imgEl.style.height = '10px';
        imgEl.style.width = '10px';
        imgEl.style.opacity = '.1';
        setTimeout(() => {
            imgEl.remove();
        }, animationMs);
    });
}

const addToList = (itemId, quantity, recipeId, name, src, element) => {
    // Fun animation!
    if (src && element) {
        animateAdd(src, element);
    }

    // Save this item to the qty
    updateQuantity(itemId, quantity, recipeId);

    // Notify the user
    // This seems like a hat on a hat with all the other stuff signaling it's been added
    // if (name) {
    //     addToast({ type: 'success', title: name, message: `${quantity} added to list.` });
    // }
};

const updateQuantity = (itemId, quantity, recipeId) => {
    // Find existing entry
    const index = activeList.value.findIndex((entry) => entry.item_id === itemId);
    const data = {
        item_id: itemId,
        recipe_id: recipeId,
        quantity
    };

    if (index === -1) {
        // Only add if there's a quantity
        if (quantity > 0) {
            activeList.value.push(data);
        }
    } else {
        activeList.value[index].quantity += quantity;

        // If recipeId is intentionally set to false, maintain the existing value
        if (recipeId !== false) {
            // Only one recipe preference is saved
            activeList.value[index].recipe_id = recipeId;
        }

        // Remove from the list entirely if less than 1
        if (activeList.value[index].quantity < 1) {
            activeList.value.splice(index, 1);
        }
    }
};

const quantityInCart = (itemId) => {
    const index = activeList.value.findIndex((entry) => entry.item_id === itemId);
    return activeList.value[index]?.quantity || 0;
}

const totalQuantity = computed(() => {
    return Object.values(activeList.value).reduce((a, b) => a + b.quantity, 0);
});

const emptyList = () => {
    activeList.value = [];
};

export default function () {
    return {
        addToList,
        updateQuantity,
        quantityInCart,
        emptyList,
        activeList,
        totalQuantity,
    };
}
