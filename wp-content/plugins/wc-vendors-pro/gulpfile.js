// Load the dependencies
var gulp = require('gulp'),
    sass = require('gulp-sass'),
    autoprefixer = require('gulp-autoprefixer'),
    minifycss = require('gulp-clean-css'),
    uglify = require('gulp-uglify'),
    rename = require('gulp-rename'),
    wpPot = require('gulp-wp-pot'),
    sort = require('gulp-sort'),
    pump = require('pump');

// Public
gulp.task('styles-public', function(cb) {
   pump([
        gulp.src( 'public/assets/css/src/*.scss' ),
        sass( { 'sourcemap=none': true, outputStyle: 'compact' } ),
        autoprefixer('last 2 version', 'safari 5', 'ie 8', 'ie 9', 'opera 12.1', 'ios 6', 'android 4'),
        gulp.dest('public/assets/css'),
        rename({suffix: '.min'}),
        minifycss(),
        gulp.dest('public/assets/css')
    ], cb);
});

gulp.task('js-public', function(cb) {
    pump([
        gulp.src('public/assets/js/src/*.js'),
        uglify(),
        rename({suffix: '.min'}),
        gulp.dest('public/assets/js/')
    ], cb);
});

// Parsley
gulp.task('parsley-style', function(cb) {
    pump([
        gulp.src( 'public/assets/lib/parsley/parsley.scss'),
        sass(  { 'sourcemap=none': true, outputStyle: 'compact' } ),
        autoprefixer('last 2 version', 'safari 5', 'ie 8', 'ie 9', 'opera 12.1', 'ios 6', 'android 4'),
        gulp.dest('public/assets/lib/parsley'),
        rename({suffix: '.min'}),
        minifycss(),
        gulp.dest('public/assets/lib/parsley')
    ], cb);
});




// Admin
gulp.task('styles-admin', function(cb) {
    pump([
        gulp.src( 'admin/assets/css/src/*.scss' ),
        sass( { 'sourcemap=none': true, outputStyle: 'compact' } ),
        autoprefixer('last 2 version', 'safari 5', 'ie 8', 'ie 9', 'opera 12.1', 'ios 6', 'android 4'),
        gulp.dest('admin/assets/css'),
        rename({suffix: '.min'}),
        minifycss(),
        gulp.dest('admin/assets/css')
    ], cb);
});

gulp.task('js-admin', function(cb) {
    pump([
        gulp.src('admin/assets/js/src/*.js'),
        uglify(),
        rename({suffix: '.min'}),
        gulp.dest('admin/assets/js/')
    ], cb);
});

// Watch
gulp.task( 'watch', function() {
    gulp.watch('public/assets/js/src/*.js', ['js-public']);
    gulp.watch('public/assets/css/src/*.scss', ['styles-public']);
    gulp.watch('public/assets/lib/parsley/*.scss', ['parsley-style']);
    gulp.watch('admin/assets/js/src/*.js', ['js-admin']);
    gulp.watch('admin/assets/css/src/*.scss', ['styles-admin']);
    gulp.watch('includes/assets/js/src/*.js', ['js-includes']);
    gulp.watch('includes/assets/css/src/*.scss', ['styles-includes']);
});

// Includes
gulp.task('styles-includes', function(cb) {
    pump([
        gulp.src( 'includes/assets/css/src/*.scss' ),
        sass( 'sass', { 'sourcemap=none': true, style: 'compact' } ),
        autoprefixer('last 2 version', 'safari 5', 'ie 8', 'ie 9', 'opera 12.1', 'ios 6', 'android 4'),
        gulp.dest('includes/assets/css'),
        rename({suffix: '.min'}),
        minifycss(),
        gulp.dest('includes/assets/css')
    ], cb);
});

gulp.task('js-includes-lib', function(cb) {
    pump([
        gulp.src('includes/assets/lib/**/*.js'),
        uglify(),
        rename({suffix: '.min'}),
        gulp.dest('includes/assets/js/')
    ], cb);
});


gulp.task('js-includes', function(cb) {
    pump([
        gulp.src('includes/assets/js/src/*.js'),
        uglify(),
        rename({suffix: '.min'}),
        gulp.dest('includes/assets/js/')
    ], cb);
});

// i18n files
gulp.task('wcvpro-pot', function (cb) {
    pump([
        gulp.src([ 'admin/**/*.php', 'public/**/*.php', 'includes/**/*.php', 'templates/**/*.php' ] ),
        sort(),
        wpPot( {
            domain: 'wcvendors-pro',
            package: 'wcvendors-pro',
            bugReport: 'https://www.wcvendors.com',
            lastTranslator: 'Jamie Madden <support@wcvendors.com>',
            team: 'WC Vendors <support@wcvendors.com>'
        } ),
        gulp.dest('languages/wcvendors-pro.pot')
    ], cb);
});


gulp.task('default', ['styles-public', 'parsley-style', 'js-public', 'styles-admin', 'js-admin', 'styles-includes', 'js-includes', 'js-includes-lib', 'wcvpro-pot' ] );

gulp.task('styles', [ 'styles-public', 'styles-admin', 'styles-includes' ] );
gulp.task('scripts', [ 'js-public', 'js-admin', 'js-includes', 'js-includes-lib' ] );
