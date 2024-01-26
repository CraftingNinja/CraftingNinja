import { ref, watch } from "vue";
import ItemsComposable from "@/Shared/Loaders/items.js";
import { useStorage } from "@/Shared/Helpers/useStorage.js";
import { cloneDeep, debounce } from "lodash";

const options = useStorage('craftingOptions', {
    ignored: {
        categories: ['Crystal']
    },
    selfSufficient: {
        // fishing: false, // Default: Prefer to purchase fish over fishing
        fishing: true, // TODO 1 - false ^
        crafting: true, // Default: Prefer to craft things over purchasing
        gathering: true, // Default: Prefer to gather things over purchasing
        adventuring: true // Default: Prefer to get mob drops over purchasing
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

const itemTracker = ref({});

const calculateItem = (item_id, requested) => {
    // This will trigger a bit too soon, just return
    if (items.value[item_id] === undefined) {
        return;
    }

    if (itemTracker.value[item_id] === undefined) {
        // What can they do to get this item?
        const canCraft = items.value[item_id].recipes.length > 0;
        const canGather = items.value[item_id].nodes.length > 0;
        const canAdventure = items.value[item_id].mobs.length > 0;
        const canFish = items.value[item_id].fishing.length > 0;
        const canShop = items.value[item_id].shops.length > 0;
        // Preferences don't matter if they can't buy the item; they'd _have_ to craft/gather/etc to get it
        const prefersToCraft = options.value.selfSufficient.crafting || ! canShop;
        const prefersToGather = options.value.selfSufficient.gathering || ! canShop;
        const prefersToAdventure = options.value.selfSufficient.adventuring || ! canShop;
        const prefersToFish = options.value.selfSufficient.fishing || ! canShop;

        if (canFish) {
            console.log(canFish, items.value[item_id]?.name, prefersToFish);
        }

        const preference = {
            type: 'unknown',
            entity: null,
        };

        if (canCraft && prefersToCraft) {
            preference.type = 'recipes';
        } else if (canGather && prefersToGather) {
            preference.type = 'nodes';
        } else if (canAdventure && prefersToAdventure) {
            preference.type = 'mobs';
        } else if (canFish && prefersToFish) {
            preference.type = 'fishing';
        } else if (canShop) {
            preference.type = 'shops';
        }

        preference.entity = resolvePreferenceEntity(item_id, preference.type);

        // Initial Setup
        itemTracker.value[item_id] = {
            yield: 1, // If yield is 3, but we only `request` 2, `needed` has to be 3
            requested: 0,
            needed: 0,
            obtained: 0,
            ignore: false,
            preference,
        };

        // If the item's category has been marked to ignore; ignore the item
        if (options.value.ignored.categories.includes(items.value[item_id].category.name)) {
            itemTracker.value[item_id].ignore = true;
        }
    }

    itemTracker.value[item_id].requested += requested;

    // If we're crafting this item, pick the recipe, and calculate any reagents
    if (itemTracker.value[item_id].preference.type === 'recipes') {
        const recipe = itemTracker.value[item_id].preference.entity;

        itemTracker.value[item_id].yield = recipe.yield;

        // Loop over all the chosen recipe's reagents to add them to the tracker
        Object.entries(recipe.reagents).forEach(([item_id, quantity]) => calculateItem(item_id, quantity));
    }
};

const resolvePreferenceEntity = (item_id, preferenceType) => {
    // TODO Entity Preferences, Per Item. For now, picking first available entity.
    let preference = {};
    if (preferenceType === 'recipes') {
        preference = recipePreferences[item_id]
            ? items.value[item_id].recipes.find((recipe) => recipe.id === recipePreferences[item_id])
            : items.value[item_id].recipes[0];
    } else if (preferenceType === 'nodes') {
        preference = items.value[item_id].nodes[0];
    } else if (preferenceType === 'mobs') {
        preference = items.value[item_id].mobs[0];
    } else if (preferenceType === 'fishing') {
        preference = items.value[item_id].fishing[0];
    } else if (preferenceType === 'shops') {
        const shop = items.value[item_id].shops[0];
        const npc = shop.npcs[0];
        preference = { shop, npc };
    }

    return preference;
};

const defaultLocationStructure = { Unknown: { items: [] } };
const locationTracker = ref(cloneDeep(defaultLocationStructure));

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
    let pointer = locationTracker.value;

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
        locationTracker.value.Unknown.items.push(item_id);
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

const calculate = () => {
    Object.values(registeredItems.value).forEach((entry) => calculateItem(entry.item_id, entry.quantity));

    // Reset the location tracker
    locationTracker.value = cloneDeep(defaultLocationStructure);

    // Loop through items, put all the possibilities (for its preference) in the location tracker
    Object.entries(itemTracker.value).forEach(([item_id, entry]) => {
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
    if (locationTracker.value.Unknown?.items.length === 0) {
        delete locationTracker.value.Unknown;
    }

    console.log('locationTracker', locationTracker.value);
};

watch(loading, debounce(calculate, 1));
watch(options, debounce(calculate, 1), { deep: true });

const reset = () => {
    registeredItems.value = [];
    recipePreferences.value = {};
    itemTracker.value = {};
};

export default function () {
    return {
        reset,
        registerWantedItem,
        options,
        locationTracker,
        itemTracker
    }
}
