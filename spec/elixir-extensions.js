
var gulp = require('gulp');
var shell = require('gulp-shell');
var Elixir = require('laravel-elixir');

var Task = Elixir.Task;

Elixir.extend('jasmine', function(params) {

    new Task('jasmine', function() {
        return gulp.src('').pipe(shell('node_modules/jasmine-node/bin/jasmine-node spec/api' + params));
    }).watch('./app/**');

});