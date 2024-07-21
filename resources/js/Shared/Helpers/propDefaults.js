/**
 * Default Values for defineProps()
 *
 * In an effort to make defineProps() more streamlined, these prop defaults are available for use.
 *
 * Note that defineProps() is a compiler macro. This limits our options on how we can use it.
 * For instance, you cannot use local variables in combination with defineProps().
 * Its definition is compiled away when `script setup` is processed (hoisted out of setup into the module scope).
 * @see https://vuejs.org/api/sfc-script-setup.html#defineprops-defineemits
 *
 * Basic Usage without a default
 *
 import { defaultString } from "@H/propDefaults";
 defineProps({ varOne: defaultString });
 *
 * Basic Usage with a default
 *
 import { numberProp } from "@H/propDefaults";
 defineProps({ varOne: numberProp(999) });
 */

export const booleanProp = (val, required = false) => ({
    type: Boolean,
    required,
    default: !!val, // Convert undefined to false
});

export const numberProp = (val, required = false) => ({
    type: Number,
    required,
    default: val || 0,
});

export const stringProp = (val, required = false) => ({
    type: String,
    required,
    default: val || "",
});

export const objectProp = (val, required = false) => ({
    type: Object,
    required,
    default() {
        return val || {};
    },
});

export const arrayProp = (val, required = false) => ({
    type: Array,
    required,
    default() {
        return val || [];
    },
});

export const functionProp = (val, required = false) => ({
    type: Function,
    required,
    default: typeof val !== "undefined" ? val : Function.prototype,
});

export const iconProp = (val, required = false) => ({
    type: [Object, Function], // Some icons are objects, others are functions
    required,
    default: typeof val !== "undefined" ? val : {},
});

export const defaultTrue = booleanProp(true);
export const defaultFalse = booleanProp();
export const defaultNumber = numberProp();
export const defaultString = stringProp();
export const defaultObject = objectProp();
export const defaultArray = arrayProp();
export const defaultFunction = functionProp();
export const defaultIcon = iconProp();

// Advanced Option
// defaultProp(variable) will detect the variable type and convert as needed.
// We lose the readability of the type in practice, so usage isn't recommended, but I like having the option.
const typeToPropConversion = {
    boolean: booleanProp,
    number: numberProp,
    string: stringProp,
    array: arrayProp,
    object: objectProp,
    function: functionProp,
};

export const defaultProp = (val) => {
    let type = Array.isArray(val) ? "array" : typeof val;
    return typeToPropConversion[type](val);
};
