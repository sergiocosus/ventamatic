var elixir = require('laravel-elixir');

require('./spec/elixir-extensions');
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

elixir(function(mix) {
    mix.sass('app.scss')
        .jasmine('');
        //.exec('node_modules/jasmine-node/bin/jasmine-node spec/api');
});