const mix = require('laravel-mix');

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel application. By default, we are compiling the Sass
 | file for the application as well as bundling up all the JS files.
 |
 */

mix.js('resources/js/app.js', 'public/js')
    .vue()
    .sass('resources/sass/app.scss', 'public/css');

mix.styles([
    // 'resources/assets/css/icons/icomoon/styles.min.css',
    'resources/assets/css/bootstrap.min.css',
    'resources/assets/css/bootstrap_limitless.css',
    'resources/assets/css/layout.min.css',
    'resources/assets/css/components.min.css',
    'resources/assets/css/colors.min.css',


], 'public/css/all.css');

mix.scripts([
    'resources/assets/js/main/jquery.min.js',
    'resources/assets/js/sidebar_secondary.js',
    'resources/assets/js/main/bootstrap.bundle.min.js',
    'resources/assets/js/plugins/loaders/blockui.min.js',
    'resources/assets/js/core/vue-youtube-embed.umd.js',
    'resources/assets/js/plugins/forms/styling/switchery.min.js',
    'resources/assets/js/plugins/ui/prism.min.js',
    'resources/assets/js/plugins/forms/styling/uniform.min.js',
    'resources/assets/js/application.js',
], 'public/js/app2.js');

mix.copy('node_modules/vue-youtube-embed/lib/vue-youtube-embed.umd.js', 'resources/assets/js/core/vue-youtube-embed.umd.js');
// mix.copy('resources/assets/js/plugins/forms/styling/switchery.min.js', 'public/js/switchery.min.js');
mix.copy('resources/assets/js/application.js', 'public/js/application.js');
