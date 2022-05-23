window._ = require('lodash');

try {
    require('bootstrap');
    window.$ = window.jQuery = require('jquery');
} catch (e) {}

/**
 * We'll load the axios HTTP library which allows us to easily issue requests
 * to our Laravel back-end. This library automatically handles sending the
 * CSRF token as a header based on the value of the "XSRF" token cookie.
 */

window.axios = require('axios');

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

axios.interceptors.request.use((config) => {
    // trigger 'loading=true' event here
    document.getElementById('overlay').style.display = 'block';
    return config;
}, (error) => {
    // trigger 'loading=false' event here
    document.getElementById('overlay').style.display = 'none';
    return Promise.reject(error);
});

axios.interceptors.response.use((response) => {
    // trigger 'loading=false' event here
    document.getElementById('overlay').style.display = 'none';
    return response;
}, (error) => {
    // trigger 'loading=false' event here
    document.getElementById('overlay').style.display = 'none';
    return Promise.reject(error);
});

$(document).on('keydown', '.multiselect', function (event) {
    // watch for keydown events on elements with class multiselect
    if (event.which === 9) { // if event is tab
        var e = $.Event('click'); // create click event
        $(event.target).parent().siblings().find('.multiselect__option--highlight:not(.multiselect__option--selected)').trigger(e); // trigger click event to select the focused option
        // event.preventDefault(); // prevent tab default action, because we already did tabNext
    }
});

/**
 * Echo exposes an expressive API for subscribing to channels and listening
 * for events that are broadcast by Laravel. Echo and event broadcasting
 * allows your team to easily build robust real-time web applications.
 */

// import Echo from 'laravel-echo';

// window.Pusher = require('pusher-js');

// window.Echo = new Echo({
//     broadcaster: 'pusher',
//     key: process.env.MIX_PUSHER_APP_KEY,
//     cluster: process.env.MIX_PUSHER_APP_CLUSTER,
//     forceTLS: true
// });
