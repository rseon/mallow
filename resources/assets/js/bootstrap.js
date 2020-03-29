/*
 * Initialize your vendors here
 */

window.jQuery = window.$ = require('jquery')
require('bootstrap')

/**
 * Add CSRF token XHR
 */
$.ajaxSetup({
    headers: {
        'X-Requested-With': 'XMLHttpRequest',
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
    },
});
