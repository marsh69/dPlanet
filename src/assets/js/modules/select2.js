import $ from 'jquery';
require('select2');

/**
 * Activate the select2 functionality on all select 2 fields
 */
export const activateSelect2 = () => {
  $('.select2').select2();
};
