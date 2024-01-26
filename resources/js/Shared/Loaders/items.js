import { nextTick, ref, watch } from "vue";
import { debounce } from "lodash";

const wanted = ref([]);
const items = ref({});
const loading = ref(false);

let findQueued = false;
let requestActive = false;
let requestActiveTimeout = null;

const findUnloaded = () => {
    if (requestActiveTimeout) {
        clearTimeout(requestActiveTimeout);
    }

    // findQueued has fulfilled it's objective
    if (findQueued) {
        findQueued = false;
    }

    if (requestActive) {
        findQueued = true;
        return;
    }

    // Force the entries to strings
    const alreadyLoaded = Object.keys(items.value).map((entry) => parseInt(entry));
    const loadMe = wanted.value.filter((entry) => ! alreadyLoaded.includes(entry));

    loading.value = requestActive = true;

    const fin = () => {
        if (findQueued) {
            findUnloaded();
        } else {
            requestActive = false;
            requestActiveTimeout = setTimeout(() => {
                if ( ! requestActive) {
                    loading.value = false;
                }
            }, 50);
        }
    }

    if (loadMe.length === 0) {
        // We need `loading` to be true for at least one tick; otherwise a watcher won't pick up that it's changed
        nextTick(fin);
    }

    axios.post(route('api.items.many'), { items: loadMe })
        .then((response) => response.data.forEach((data) => (items.value[data.id] = data)))
        .finally(fin);
}

// Expecting `item` to be { item_id, recipe_id, quantity }
const loadItem = (item_id) => {
    item_id = parseInt(item_id);
    if ( ! wanted.value.includes(item_id)) {
        wanted.value.push(item_id);
    }
}

// We only need to debounce a little bit; item additions will happen in rapid succession
watch(wanted, debounce(findUnloaded, 10), { immediate: true, deep: true });

const recipes = ref({});

// Expecting `item` to be { item_id, recipe_id, quantity }
const loadReagents = () =>
    Object.values(items.value).forEach((item) =>
        item.recipes?.forEach((recipe) => {
            if (recipes.value[recipe.id] === undefined) {
                recipes.value[recipe.id] = recipe;
                Object.keys(recipe.reagents).forEach(loadItem);
            }
        })
    );

// Debounce time should be less than the findUnloaded debouncer
watch(items, debounce(loadReagents, 5), { immediate: true, deep: true });

export default function () {
    return {
        loading,
        loadItem,
        findUnloaded,
        wanted,
        items,
        recipes
    }
}
