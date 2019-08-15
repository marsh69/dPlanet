import dayjs from 'dayjs';

/**
 * @param {string} format
 * @returns {function(string): string}
 */
export const formatDate = (format) => (dateString) => {
  return dayjs(dateString).format(format);
};

/**
 * @param {string} dateString
 * @returns {string}
 */
export const formatDateDefault = (dateString) =>
  formatDate('DD-MM-YYYY')(dateString);
