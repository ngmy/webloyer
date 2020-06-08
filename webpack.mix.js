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

mix.js('src/Webloyer/Infra/Framework/Laravel/Resources/js/app.js', 'public/vendor/webloyer/js')
    .less('src/Webloyer/Infra/Framework/Laravel/Resources/less/app.less', 'public/vendor/webloyer/css')
    .version();
