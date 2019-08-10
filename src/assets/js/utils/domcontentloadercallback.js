/**
 * @param {Array<Function>} callbacks
 */
export const domContentLoadedCallback = callbacks => {
  window.addEventListener('DOMContentLoaded', () => {
    callbacks.forEach(callback => {
      callback();
    });
  });
};
