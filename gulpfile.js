const gulp = require('gulp')
// var glob = require('glob');
const elixir = require('laravel-elixir')
require('laravel-elixir-vueify')
require('laravel-elixir-vue-2')
/*
 |--------------------------------------------------------------------------
 | Elixir Asset Management
 |--------------------------------------------------------------------------
 |
 | Elixir provides a clean, fluent API for defining some basic Gulp tasks
 | for your Laravel application. By default, we are compiling the Sass
 | file for our application, as well as publishing vendor resources.
 |
 */

elixir((mix) => {
  mix.styles([
    '../../../node_modules/vuetify/dist/vuetify.min.css',
    '../../../node_modules/froala-editor/css/froala_editor.pkgd.min.css',
    // '../../../node_modules/font-awesome/css/font-awesome.min.css',
    '../../../node_modules/froala-editor/css/plugins/help.min.css',
    '../../../node_modules/froala-editor/css/plugins/image.min.css',
    '../../../node_modules/froala-editor/css/plugins/colors.min.css',
    '../../../node_modules/froala-editor/css/plugins/quick_insert.min.css',
    '../../../node_modules/froala-editor/css/plugins/video.min.css',
    '../../../node_modules/froala-editor/css/plugins/table.min.css',
    '../../../node_modules/froala-editor/css/plugins/fullscreen.min.css',
    '../../../node_modules/froala-editor/js/plugins/wiris/icons/font/css/wirisplugin.css',
    '../../../node_modules/froala-editor/css/froala_style.min.css'
  ])
  mix.sass('app.scss')
  // mix.copy('resources/assets/vendor/froala-editor', 'public/vendor/froala-editor')

    // VueJS file
    // mix.webpack('vendor.js');
    // mix.webpack('resources.vue.js');
  mix.webpack('app.js')

    // Versioning
  mix.version(['css/app.css', 'css/all.css', 'js/app.js'])

    /* mix.browserSync({
        proxy: 'indiewise.dev'
    }); */
})
