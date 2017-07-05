/**
 * Principais helper functions
 */
window.helper = require('./helpers.js')

window._ = require('lodash');

/**
 * We'll load jQuery and the Bootstrap jQuery plugin which provides support
 * for JavaScript based Bootstrap features such as modals and tabs. This
 * code may be modified to fit the specific needs of your application.
 */

try {
  window.$ = window.jQuery = require('jquery');

  require('bootstrap');
  require('bootstrap-switch');
} catch (e) {}

/**
 * We'll load the axios HTTP library which allows us to easily issue requests
 * to our Laravel back-end. This library automatically handles sending the
 * CSRF token as a header based on the value of the "XSRF" token cookie.
 */

window.axios = require('axios');

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

/**
 * Next we will register the CSRF Token as a common header with Axios so that
 * all outgoing HTTP requests automatically have it attached. This is just
 * a simple convenience so we don't have to attach every token manually.
 */

let token = document.head.querySelector('meta[name="csrf-token"]');

if (token) {
  window.axios.defaults.headers.common['X-CSRF-TOKEN'] = token.content;
} else {
  console.error('CSRF token not found: https://laravel.com/docs/csrf#csrf-x-csrf-token');
}

/**
 * Registra o toastr como ferramenta de notificações.
 */
window.toastr = require('toastr')
window.toastr.options.positionClass = 'toast-bottom-right'

/**
 * Registra o Bootstrap3 Dialog para auxiliar na exibição das janelas modais.
 */
window.BootstrapDialog = require('bootstrap3-dialog')
window.BootstrapDialog.defaultOptions.closeByBackdrop = false
window.BootstrapDialog.defaultOptions.closeByKeyboard= false

/**
 * Registra o Collect.js para trabalhar com arrays e objetos.
 */
window.collect = require('collect.js')

/**
 * Registra o SpeakingURL para sanitizar strings.
 */
window.getSlug = require('speakingurl')

/**
 * A markdown parser and compiler.
 */
window.marked = require('marked')

/**
 * Echo exposes an expressive API for subscribing to channels and listening
 * for events that are broadcast by Laravel. Echo and event broadcasting
 * allows your team to easily build robust real-time web applications.
 */

// import Echo from 'laravel-echo'

// window.Pusher = require('pusher-js');

// window.Echo = new Echo({
//     broadcaster: 'pusher',
//     key: 'your-pusher-key'
// });
