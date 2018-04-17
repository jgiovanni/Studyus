/**
 * Vue is a modern JavaScript library for building interactive web interfaces
 * using reactive data binding and reusable components. Vue's API is clean
 * and simple, leaving you to focus on building your next great project.
 */

import Vue from 'vue'

// window._ = require('lodash');
window._ = require('underscore')
window.swal = require('sweetalert')
/**
 * We'll load jQuery and the Bootstrap jQuery plugin which provides support
 * for JavaScript based Bootstrap features such as modals and tabs. This
 * code may be modified to fit the specific needs of your application.
 */

try {
  window.$ = window.jQuery = require('jquery')
  window.moment = require('moment')
  window.momentTimeZone = require('moment-timezone/builds/moment-timezone-with-data.js')

  // Require Froala Editor js file.
  require('froala-editor/js/froala_editor.pkgd.min')
  // Require Froala Plugins
  // require('froala-editor/js/plugins/wiris/integration/WIRISplugins')
  // require('froala-editor/js/plugins/wiris/wiris')
  require('froala-editor/js/plugins/align.min')
  require('froala-editor/js/plugins/help.min')
  require('froala-editor/js/plugins/image.min')
  require('froala-editor/js/plugins/colors.min')
  require('froala-editor/js/plugins/quote.min')
  require('froala-editor/js/plugins/quick_insert.min')
  require('froala-editor/js/plugins/video.min')
  require('froala-editor/js/plugins/lists.min')
  require('froala-editor/js/plugins/table.min')
  require('froala-editor/js/plugins/fullscreen.min')
  require('froala-editor/js/plugins/word_paste.min')

// Require Froala Editor css files.
//   require('froala-editor/css/froala_editor.pkgd.min.css')
//   require('font-awesome/css/font-awesome.css')
//   require('froala-editor/css/froala_style.min.css')

    // require('bootstrap-sass');
} catch (e) {}

/**
 * We'll load the axios HTTP library which allows us to easily issue requests
 * to our Laravel back-end. This library automatically handles sending the
 * CSRF token as a header based on the value of the "XSRF" token cookie.
 */

window.axios = require('axios')

window.axios.defaults.baseURL = '/api'

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest'

/**
 * Next we will register the CSRF Token as a common header with Axios so that
 * all outgoing HTTP requests automatically have it attached. This is just
 * a simple convenience so we don't have to attach every token manually.
 */

let token = document.head.querySelector('meta[name="csrf-token"]')

if (token) {
  window.axios.defaults.headers.common['X-CSRF-TOKEN'] = token.content
} else {
  console.error('CSRF token not found: https://laravel.com/docs/csrf#csrf-x-csrf-token')
}

/**
 * We'll make any existing vue-resource $http namespaces compatible with axios
 */
Vue.prototype.$http = axios;

/**
 * Resource duplicate of vue-resource for axios
 * Inspired by https://github.com/pagekit/vue-resource/blob/develop/src/resource.js
 */
let URI = require('urijs');
let URITemplate = require('urijs/src/URITemplate');
function Resource (url, params, actions, options) {
  let resource = {};
  actions = Object.assign({},
    Resource.actions,
    actions
  );

  _.each(actions, (action, name) => {
    action = merge({url, params: Object.assign({}, params)}, options, action);

    resource[name] = function () {
      if (options)
        debugger;
      let args = opts(action, arguments);
      return (Vue.prototype.$http)(args);
    };
  });

  return resource;
}
function merge(target) {
  let ref = [], slice = ref.slice;
  let args = slice.call(arguments, 1);

  args.forEach(source => {
    merger(target, source, true);
  });

  return target;
}
function merger(target, source, deep) {
  let key;
  for (key in source) {
    if (deep && (_.isObject(source[key]) || _.isArray(source[key]))) {
      if (_.isObject(source[key]) && !_.isObject(target[key])) {
        target[key] = {};
      }
      if (_.isArray(source[key]) && !_.isArray(target[key])) {
        target[key] = [];
      }
      merger(target[key], source[key], deep);
    } else if (source[key] !== undefined) {
      target[key] = source[key];
    }
  }
}
function opts(action, args) {
  let options = Object.assign({}, action), params = {}, body;
  // Use URI Template
  let template = new URITemplate(options.url);
  switch (args.length) {

    case 2:
      params = args[0];
      body = args[1];
      break;

    case 1:
      if (/^(POST)$/i.test(options.method)) {
        body = args[0];
      } else if (/^(PUT|PATCH)$/i.test(options.method)) {
        params = args[1];
      } else {
        params = args[0];
      }

      break;

    case 0:

      break;

    default:

      throw 'Expected up to 2 arguments [params, body], got ' + args.length + ' arguments';
  }

  options.data = body;
  options.params = Object.assign({}, action.params, options.params, params);
  options.url = template.expand(options.params);

  // clean variables from params if used in url template
  let usedParams = _.isObject(template.parts[1]) ? _.pluck(template.parts[1].variables, 'name') : [];
  _.each(usedParams, function (param) {
    delete options.params[param]
  });

  return options;
}
Resource.actions = {
  get: {method: 'GET'},
  post: {method: 'POST'},
  save: {method: 'POST'},
  query: {method: 'GET'},
  update: {method: 'PUT'},
  put: {method: 'PUT'},
  remove: {method: 'DELETE'},
  delete: {method: 'DELETE'}

};
// Resource END
Vue.prototype.$resource = Resource;

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
