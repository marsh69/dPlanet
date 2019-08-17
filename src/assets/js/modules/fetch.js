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

/**
 * @param {string} url
 * @param {Object<string, string | int | boolean>} body
 * @returns {Promise<any | never>}
 */
export const postFormData = (url, body) => {
  const formData = new URLSearchParams();

  Object.keys(body).forEach((key) => formData.set(key, body[key]));

  return fetch(url, {
    method: 'POST',
    body: formData,
    headers: {
      accept: 'application/json',
    },
  }).then((response) => response.json());
};
