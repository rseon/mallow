const mix = require('laravel-mix')

mix
    .setPublicPath('public')
    .js('resources/assets/js/app.js', 'js')
    .extract(['jquery', 'popper.js', 'bootstrap'])
    .sass('resources/assets/sass/vendor.scss', 'css')
    .sass('resources/assets/sass/app.scss', 'css')
    .sourceMaps()
    .version()
