/**
 * @param {Object<string, string>} selectors
 * @returns {Object<string, Element>}
 */
export const selectElements = (selectors) => {
  return Object.keys(selectors).reduce((result, selector) => {
    result[selector] = document.querySelector(selectors[selector]);
    return result;
  }, {});
};
