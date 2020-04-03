/*
 * Include vendors initialization
 */
require('./bootstrap')

/*
 * Start coding !
 */

// Test AJAX
$(function() {
    $.post('/ajax', { name: 'Mallow'})
        .then(response => {
            console.log(response, response.data)
        })
        .catch(error => {
            console.log(error, error.data)
        })
})