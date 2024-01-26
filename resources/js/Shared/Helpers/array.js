import { without } from "lodash";

export const toggleValue = (array, value) => {
    if (array.includes(value)) {
        array = without(array, value);
    } else {
        array.push(value);
    }
    return array;
}
