const mix = require('laravel-mix');

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel applications. By default, we are compiling the CSS
 | file for the application as well as bundling up all the JS files.
 |
 */

mix.js('resources/js/app.js', 'public/js')
    .js([
        'vendor/ajaxorg/ace-builds/src-noconflict/ace.js',
        'vendor/ajaxorg/ace-builds/src-noconflict/theme-github.js',
        'vendor/ajaxorg/ace-builds/src-noconflict/mode-php.js',
        'vendor/ajaxorg/ace-builds/src-noconflict/worker-php.js',
    ], 'public/js')
    .js('vendor/lou/multi-select/js/jquery.multi-select.js', 'public/js')
    .postCss('resources/css/app.css', 'public/css', [
        require('postcss-import'),
        require('tailwindcss'),
        require('autoprefixer'),
    ])
    .css('vendor/lou/multi-select/css/multi-select.css', 'public/css')
    .less('resources/less/app.less', 'public/css')
    .options({
        processCssUrls: false
    });
