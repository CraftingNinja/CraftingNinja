export const ucfirst = (str) => str.charAt(0).toUpperCase() + str.substring(1);

export const ucwords = (str) => str.split(" ").map(ucfirst).join(" ");

export const camelCase = (str) => {
    const u = ucwords(str).split(" ").join("");
    return u.charAt(0).toLowerCase() + u.substring(1);
};
