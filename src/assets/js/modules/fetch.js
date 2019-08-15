/**
 * @param {string} url
 * @param {Object} params
 * @param {Object} headers
 * @returns {Promise<any | never>}
 */
export const fetchData = (url, params = {}, headers = {}) => {
  const queryString = Object.keys(params)
    .map((paramName) => `${paramName}=${params[paramName]}`)
    .join('&');

  return fetch(`${url}?${queryString}`, {
    headers,
  }).then((response) => response.json());
};
