import Vue from 'vue'

Vue.filter('mFormat', (value, format) => {
  return moment(value).format(format)
})
