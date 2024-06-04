window._ = require('lodash')

/**
 * We'll load the axios HTTP library which allows us to easily issue requests
 * to our Laravel back-end. This library automatically handles sending the
 * CSRF token as a header based on the value of the "XSRF" token cookie.
 */

window.axios = require('axios')

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest'

window.$ = window.jQuery = require('jquery')

window.moment = require('moment')

window.select2 = require('select2')

import 'bootstrap/js/src/modal'
import 'bootstrap/js/src/alert'
import 'bootstrap/js/src/dropdown'
import 'bootstrap/js/src/tab'
import 'bootstrap/js/src/carousel'
// import 'admin-lte'
import 'datatables.net'
import 'datatables.net-bs4'
import 'daterangepicker'

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
