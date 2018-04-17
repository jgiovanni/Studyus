/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

import Vue from 'vue'
require('./bootstrap')
require('./filters')
import Vuetify from 'vuetify'
import VueResource from 'vue-resource'
import VeeValidate from 'vee-validate'
import VueFroala from 'vue-froala-wysiwyg'
// import VueAnalytics from 'vue-ua'

Vue.use(Vuetify)
Vue.use(VueResource)
Vue.use(VeeValidate)
Vue.use(require('vue-localforage'))
Vue.use(VueFroala)
/* Vue.use(VueAnalytics, {
  appName: 'IndieWise',
  appVersion: '2.0',
  trackingId: 'UA-27155404-17',
  debug: false, // Whether or not display console logs debugs (optional)
}); */

Vue.filter('vmTimeAgo', function (date, suffix, from) {
  let dateFrom
  if (typeof date === 'undefined' || date === null) {
    return ''
  }

  date = window.moment(date)
  if (!date.isValid()) {
    return ''
  }

  dateFrom = window.moment(from)
  if (typeof from !== 'undefined' && dateFrom.isValid()) {
    return date.from(dateFrom, suffix)
  }

  return date.fromNow(suffix)

})
Vue.filter('vmDateFormat', function (value, format) {
  function amDateFormatFilter (value, format) {
    if (window._.isUndefined(value) || window._.isNull(value)) {
      return ''
    }

    let date = window.moment(value)
    if (!date.isValid()) {
      return ''
    }

    return date.format(format)
  }

  return amDateFormatFilter(value, format)
})

Vue.mixin({
  computed: {
    isIOS () {
      return !!(navigator.userAgent.match(/iPhone/i) || navigator.userAgent.match(/iPod/i) || navigator.userAgent.match(/iPad/i))
    },
    isAndroid () {
      return !!navigator.userAgent.match(/Android/i)
    },
    isInstructorsTasks () {
      return location.pathname.indexOf('/app/assignments') !== -1
    },
    isInstructorsClassrooms () {
      return location.pathname.indexOf('/app/classrooms') !== -1
    },
  }
})

Vue.http.options.root = '/api'
Vue.http.interceptors.push((request, next) => {
  let token, headers

  token = localStorage.getItem('jwt-token')
  headers = request.headers || (request.headers = {})

  request.headers.set('X-CSRF-TOKEN', $('meta[name="csrf-token"]').attr('content'))
  if (token !== null && token !== 'undefined') {
    request.headers.set('Authorization', token)
  }

  // Show Spinners in all components where they exist
  if (_.contains(['GET', 'POST', 'PUT'], request.method.toUpperCase()) && request.root === '/api') {
    /* if (this.$refs && this.$refs.spinner && !request.params.hideLoader) {
      switch (request.method.toUpperCase()) {
        case 'GET':
          this.$refs.spinner.show({text: 'Loading'});
          break;
        case 'POST':
          this.$refs.spinner.show({text: 'Saving'});
          break;
        case 'PUT':
          this.$refs.spinner.show({text: 'Updating'});
          break;
      }
    } */
  }

  // continue to next interceptor
  next(response => {
    // Hide Spinners in all components where they exist
    if (this.$refs && this.$refs.spinner && !_.isUndefined(this.$refs.spinner._started)) {
      this.$refs.spinner.hide()
    }

    if (response.status && response.status.code === 401) {
      localStorage.removeItem('jwt-token')
    }
    if (response.headers && response.headers.Authorization) {
      localStorage.setItem('jwt-token', response.headers.Authorization)
    }
    if (response.entity && response.entity.token && response.entity.token.length > 10) {
      localStorage.setItem('jwt-token', 'Bearer ' + response.entity.token)
    }
  })
})

Vue.component('cell', {template: '<td><slot></slot></td>'})

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

// Vue.component('example', require('./components/Example.vue'))
// Vue.component('', require('./components/instructors/dashboard.vue'))
const app = new Vue({
  el: '#app',
  http: {
    headers: {
      'X-CSRF-TOKEN': window.$('meta[name="csrf-token"]').attr('content')
      // 'Authorization': localStorage.getItem('jwt-token')
    }
  },
  components: {
    instructorsDashboard: require('./components/instructors/dashboard.vue'),
    instructorsTasks: require('./components/instructors/tasks.vue'),
    instructorsActiveTask: require('./components/instructors/active-task.vue'),
    instructorsTask: require('./components/instructors/task.vue'),
    instructorsTaskSettings: require('./components/instructors/task-settings.vue'),
    instructorsClassrooms: require('./components/instructors/classrooms.vue'),
    quickCreateTask: require('./components/instructors/quick-create-task.vue'),
    quickCreateClassroom: require('./components/instructors/quick-create-classroom.vue'),
    instructorsClassroomSettings: require('./components/instructors/classroom-settings.vue'),
    // Misc Components
    listenText: require('./components/listen-text.vue'),
    actionButton: require('./components/action-button.vue'),
  },
  data () {
    return {
      mainDrawer: {
        open: true,
        mini: true,
        right: false
      },
      globalAlerts: [],
      globalToasts: [],
      UNIQUE_ID_RETRIES: 9999,
      user: {
        first_name: 'Sabrina',
        last_name: 'Bain',
        id: '4a9d9f34-2e2e-46de-9d37-828dc180ede7'
      },
    }
  },
  watch: {},
  methods: {
    handleApiError (response) {
      this.$emit('alert_user', {
        message: response.body.message,
        type: 'error'
      })
    }
  },
  created () {
    this.$storageConfig({
      // driver: 'localStorageWrapper', // if you want to force a driver
      // name: 'iw', // name of the database and prefix for your data, it is 'lf' by default
      version: 1.0, // version of the database, you shouldn't have to use this
      storeName: 'db', // name of the table
      description: ''
    })

    this.$on('alert_user', data => {
      let ID = _.uniqueId('alert_')
      this.globalAlerts.push({
        id: ID,
        message: data.message,
        type: data.type,
        show: true,
        time: data.time
      })

      // We want the message to expire after 8 seconds
      setTimeout(() => {
        let thisAlert = _.findWhere(this.globalAlerts, {id: ID})
        thisAlert.show = false
        setTimeout(() => {
          this.globalAlerts = _.reject(this.globalAlerts, alert => {
            return alert.id === thisAlert.id
          })
        }, 200)
      }, data.time || 8000)
    })

    this.$on('toast_user', data => {
      let ID = _.uniqueId('toast_')
      this.globalAlerts.push({
        id: ID,
        text: data.message,
        type: data.type,
        mode: this.isIOS || this.isAndroid(),
        show: true,
        time: data.time
      })

      // We want the message to expire after 8 seconds
      setTimeout(() => {
        let thisAlert = _.findWhere(this.globalAlerts, {id: ID})
        thisAlert.show = false
        setTimeout(() => {
          this.globalAlerts = _.reject(this.globalAlerts, alert => {
            return alert.id === thisAlert.id
          })
        }, 200)
      }, data.time || 8000)
    })
  },
  mounted () {
    /* let alertInterval = setInterval(() => {
      let choices = ['success', 'warning', 'danger', 'primary', 'secondary'];
      this.$emit('alert_user', {
        message: 'This is a test',
        type: choices[_.random(0, 4)]
      })
    }, 6000) */
  }
})
