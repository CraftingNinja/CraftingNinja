import { reactive, ref, watch } from "vue";
import ItemsComposable from "@/Shared/Loaders/items.js";
import { useStorage } from "@/Shared/Helpers/useStorage.js";
import { debounce } from "lodash";

const options = useStorage('craftingOptions', {
    ignored: {
        categories: ['Crystal']
    },
    selfSufficient: {
        f: true, // Default: Prefer to fish for items over purchasing
        r: true, // Default: Prefer to craft things over purchasing
        n: true, // Default: Prefer to gather things over purchasing
        m: true, // Default: Prefer to get mob drops over purchasing
    },
});

const { items, loadItem, loading } = ItemsComposable();

const registeredItems = ref([]);
const recipePreferences = ref({ /* itemId: recipeId */ });

// Expecting `item` to be { item_id, recipe_id, quantity }
const registerWantedItem = (entry) => {
    registeredItems.value.push(entry);
    recipePreferences.value[entry.item_id] = entry.recipe_id;
    loadItem(entry.item_id);
}

const defaultLocationStructure = { Unknown: { items: [] } };

const state = reactive({
    items: {},
    locations: structuredClone(defaultLocationStructure),
    preferences: {
        /* 1234: 'm', */
    },
});

const calculateItem = (item_id, requested, trigger, force = false) => {
    // This will trigger a bit too soon, just return
    if (items.value[item_id] === undefined) {
        return;
    }

    const item = items.value[item_id];

    if (force || state.items[item_id] === undefined) {
        const [prefType, prefId] = state.preferences[item_id]?.split('|') || [null, null];

        const can = {
            r: item.recipes.length > 0,
            n: item.nodes.length > 0,
            m: item.mobs.length > 0,
            f: item.fishing.length > 0,
            s: item.shops.length > 0,
        };

        const ss = options.value.selfSufficient;

        const prefersTo = {
            // Prefers to Craft is they're self-sufficient in crafting, or they can't shop for the item
            // Additionally, there's an override with their preference override
            r: prefType === 'r' || (prefType === null && (ss.r || ! can.s)),
            n: prefType === 'n' || (prefType === null && (ss.n || ! can.s)),
            m: prefType === 'm' || (prefType === null && (ss.m || ! can.s)),
            f: prefType === 'f' || (prefType === null && (ss.f || ! can.s)),
            s: prefType === 's' || (prefType === null && can.s),
        };

        const preference = {
            type: false,
            entity: null,
        };

        const preferenceType = {
            // This is the order in which preferences will be considered.
            r: 'recipes',
            n: 'nodes',
            m: 'mobs',
            f: 'fishing',
            s: 'shops'
        };

        Object.entries(preferenceType).forEach(([key, value]) => {
            if (!preference.type && can[key] && prefersTo[key]) {
                preference.type = value;
            }
        });

        preference.entity = resolvePreferenceEntity(item_id, preference.type, prefId);
        preference.identifier = preference.type.charAt(0) + '|' + preference.entity.id;

        // Initial Setup
        state.items[item_id] = {
            ...item,
            yield: 1, // If yield is 3, but we only `request` 2, `needed` has to be 3
            requested: 0,
            needed: 0,
            obtained: 0,
            ignore: false,
            triggers:{},
            preference,
        };

        // If the item's category has been marked to ignore; ignore the item
        if (options.value.ignored.categories.includes(item.category.name)) {
            state.items[item_id].ignore = true;
        }
    }

    // Add what initially triggered this item; there can be multiple triggers (recipes) that caused this to be added
    if (trigger) {
        state.items[item_id].triggers[trigger.id] = trigger;
    }

    state.items[item_id].requested += requested;

    // If we're crafting this item, pick the recipe, and calculate any reagents
    if (state.items[item_id].preference.type === 'recipes') {
        const recipe = state.items[item_id].preference.entity;

        state.items[item_id].yield = recipe.yield;

        // Loop over all the chosen recipe's reagents to add them to the tracker
        Object.entries(recipe.reagents).forEach(([item_id, quantity]) => calculateItem(item_id, quantity, trigger || item, force));
    }
};

const resolvePreferenceEntity = (item_id, preferenceType, prefId) => {
    // TODO Entity Preferences, Per Item. For now, picking first available entity.
    const item = items.value[item_id];

    if (!item[preferenceType] || preferenceType === 'unknown') {
        return;
    }

    // If prefId is defined, the formula is the same for everything.
    // The preferenceType matches an item's list of entities (item.nodes, item.mobs, etc)
    if (prefId) {
        return item[preferenceType].find((entity) => entity.id === parseInt(prefId));
    }

    // For recipes, as a backup, it was possibly registered with a recipe in mind
    if (preferenceType === 'recipes' && recipePreferences[item_id]) {
        return item[preferenceType].find((recipe) => recipe.id === recipePreferences[item_id]);
    }

    // Default: Just return the first entity
    return item[preferenceType][0];
};

// const locationTracker = ref(cloneDeep(defaultLocationStructure));

const dataRepository = ref({
    mob: {},
    npc: {},
    node: {},
    fishingSpot: {},
    location: {},
});

const registerData = (type, data) => {
    if (dataRepository.value[type][data.id] === undefined) {
        dataRepository.value[type][data.id] = data;
    }
};

const updateLocationTrackerPointer = (entity, locationKey = 'location') => {
    // let pointer = locationTracker.value;
    let pointer = state.locations;

    let locationName = [];

    if (entity[locationKey]?.parent?.parent) {
        registerData('location', entity[locationKey].parent.parent);
        locationName.push(entity[locationKey].parent.parent.name);
        // pointer[entity[locationKey].parent.parent.name] ??= {};
        // pointer = pointer[entity[locationKey].parent.parent.name];
    }

    if (entity[locationKey]?.parent) {
        registerData('location', entity[locationKey].parent);
        locationName.push(entity[locationKey].parent.name);
        // pointer[entity[locationKey].parent.name] ??= {};
        // pointer = pointer[entity[locationKey].parent.name];
    }

    if (entity[locationKey]) {
        registerData('location', entity[locationKey]);
        locationName.push(entity[locationKey].name);
        // pointer[entity[locationKey].name] ??= {};
        // pointer = pointer[entity[locationKey].name];
    }

    // Where there's `zone`, there's bound to be `area`
    if (locationKey === 'zone') {
        if (entity.area) {
            registerData('location', entity.area);
            locationName.push(entity.area.name);
            // pointer[entity.area.name] ??= {};
            // pointer = pointer[entity.area.name];
        }
    }

    locationName = locationName.join(' - ');

    pointer[locationName] ??= {};
    pointer = pointer[locationName];

    return pointer;
}

const calculateShops = (item_id) => {
    items.value[item_id].shops.forEach((shop) => {
        shop.npcs.forEach((npc) => {
            registerData('npc', npc);
            let pointer = updateLocationTrackerPointer(npc);

            // pointer['items'] ??= {};
            // pointer = pointer['items'];

            pointer[item_id] ??= shop;

            // pointer['shops'] ??= {};
            // pointer = pointer['shops'];
            //
            // pointer[npc.id] ??= [];
            // pointer = pointer[npc.id];
            //
            // if (pointer.indexOf(item_id) === -1) {
            //     pointer.push(item_id);
            // }
        });
    });
};

const calculateMobs = (item_id) => {
    items.value[item_id].mobs.forEach((mob) => {
        registerData('mob', mob);
        let pointer = updateLocationTrackerPointer(mob);

        // pointer['items'] ??= {};
        // pointer = pointer['items'];

        pointer[item_id] ??= mob;

        // pointer['mobs'] ??= {};
        // pointer = pointer['mobs'];
        //
        // pointer[mob.id] ??= [];
        // pointer = pointer[mob.id];
        //
        // if (pointer.indexOf(item_id) === -1) {
        //     pointer.push(item_id);
        // }
    });
};

const calculateNodes = (item_id) => {
    items.value[item_id].nodes.forEach((node) => {
        registerData('node', node);
        let pointer = updateLocationTrackerPointer(node, 'zone');

        // pointer['items'] ??= {};
        // pointer = pointer['items'];

        pointer[item_id] ??= node;

        // pointer['nodes'] ??= {};
        // pointer = pointer['nodes'];
        //
        // pointer[node.id] ??= [];
        // pointer = pointer[node.id];
        //
        // if (pointer.indexOf(item_id) === -1) {
        //     pointer.push(item_id);
        // }
    });
}

const calculateFishing = (item_id) => {
    items.value[item_id].fishing.forEach((fishing) => {
        registerData('fishingSpot', fishing);

        let pointer = updateLocationTrackerPointer(fishing, 'zone');

        // pointer['items'] ??= {};
        // pointer = pointer['items'];

        pointer[item_id] ??= fishing;

        // pointer['fishingSpots'] ??= {};
        // pointer = pointer['fishingSpots'];
        //
        // pointer[fishing.id] ??= [];
        // pointer = pointer[fishing.id];
        //
        // if (pointer.indexOf(item_id) === -1) {
        //     pointer.push(item_id);
        // }
    });
}

const calculateRecipes = (item_id) => {
    // TODO
}

const calculateLocation = (item_id, preference) => {
    // Because of calculate item, if a preference is set, we know its equivalent dataset exists
    if ( ! preference) {
        // locationTracker.value.Unknown.items.push(item_id);
        state.locations.Unknown.items.push(item_id);
    } else if (preference === 'recipes') {
        calculateRecipes(item_id);
    } else if (preference === 'shops') {
        calculateShops(item_id);
    } else if (preference === 'nodes') {
        calculateNodes(item_id);
    } else if (preference === 'mobs') {
        calculateMobs(item_id);
    } else if (preference === 'fishing') {
        calculateFishing(item_id);
    }
}

// TODO 1 - Auction House Preference?
// TODO 1 - Allow permanent (user-stored) "ignore" of a method (I don't want to kill X to get Y; I don't want to buy Z over in W)
// TODO 1 - Allow permanent (user-stored) "preference" of a method (For item B, I only want to kill D to get it)
// TODO 1 - UNKNOWNS NEED A REPORTING/COMMENT SECTION, With a tally that will let people know that it's been reported

const calculate = (force = false) => {
    loadPreferences();

    Object.values(registeredItems.value).forEach(
        (entry) => calculateItem(entry.item_id, entry.quantity, false, force)
    );

    // Reset the location tracker
    // locationTracker.value = cloneDeep(defaultLocationStructure);
    state.locations = structuredClone(defaultLocationStructure);

    // Loop through items, put all the possibilities (for its preference) in the location tracker
    Object.entries(state.items).forEach(([item_id, entry]) => {
        // Ensure things are incremented per yield
        entry.needed = Math.ceil(entry.requested / entry.yield) * entry.yield;

        // If this item is ignored, mark it obtained
        if (entry.ignore) {
            entry.obtained = entry.needed;
        } else if (entry.obtained < entry.needed) {
            // Reference the item in a location hierarchy
            // Sometimes we know area, zone and even a zone parent
            calculateLocation(item_id, entry.preference.type);
        }
    });

    // Clean up location tracker, specifically the Unknown
    // if (locationTracker.value.Unknown?.items.length === 0) {
    if (state.locations.Unknown?.items.length === 0) {
        // delete locationTracker.value.Unknown;
        delete state.locations.Unknown;
    }

    console.log('state', state);
};

watch(loading, debounce(calculate, 1));
watch(options, debounce(calculate, 1), { deep: true });

const reset = () => {
    registeredItems.value = [];
    recipePreferences.value = {};
    state.items = {};
};

const loadPreferences = () => {
    // TODO 1 - These should come from page load
    state.preferences = JSON.parse(localStorage.getItem('preferences') ?? '{}');
};

const savePreference = (item_id, selectedPreference) => {
    // TODO 1 - save preference to database
    state.preferences[item_id] = selectedPreference;
    localStorage.setItem('preferences', JSON.stringify(state.preferences));
    calculate(true);
};

export default function () {
    return {
        reset,
        registerWantedItem,
        options,
        state,
        savePreference
    }
}
