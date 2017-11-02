const mix      = require('laravel-mix');
const min = '.min';

mix.setPublicPath('assets');
mix.setResourceRoot('../');

mix.js('src/admin/nst_main.js', `assets/js/ninja-split-testing${min}.js`);