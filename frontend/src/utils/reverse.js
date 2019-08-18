/**
 * Example function
 *
 * Reverse a given string
 * @param {string} text
 * @returns {string}
 */
export const reverse = text =>
  text
    .split("")
    .reverse()
    .join("");
