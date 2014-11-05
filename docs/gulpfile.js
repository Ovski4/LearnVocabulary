//TODO minimize and include all js files
//TODO minimize img
//TODO try with (dev) and prod directory as arguments


/**
 * Here is the process of the different tasks
 *  => Development tasks
 *   - lint js files
 *   - watch js files to lint them automatically
 *  => Production tasks
 *   - Move src and web directories from /dev to /prod (the next tasks must wait this one to be finished)
 *   - Concatenate js files when they are in the same directory
 *   - remplace js all scripts includes contained between {# startscript my-concat-js-file.js #} and {# endscript #} by only one (my-concat-js-file.js)
 */

/**************/
/*** SETUP ****/
/**************/

// variables
var dev_directory = 'dev/';
var prod_directory = 'prod/';

// Include gulp
var gulp = require('gulp');

// Include plugins
var del = require('del');
var sass = require('gulp-sass');
var shell = require('gulp-shell')
var jshint = require('gulp-jshint');
var concat = require('gulp-concat');
var uglify = require('gulp-uglify');
var rename = require('gulp-rename');
var replace = require('gulp-replace');

/******************/
/*** DEV TASKS ****/
/******************/

// Lint Task
gulp.task('lint', function() {
    return gulp.src(dev_directory+'web/**/*.js')
        .pipe(jshint())
        .pipe(jshint.reporter('default'))
    ;
});

// Watch Files For Changes
gulp.task('watch', function() {
    gulp.watch('web/**/*.js', ['lint']);
});

/*******************/
/*** PROD TASKS ****/
/*******************/


//Remove prod directory if exists
gulp.task('clean', function() {
    del([prod_directory], function (err) {});
});

// Move files in prod
gulp.task('move', ['clean'], function() {
    return gulp.src(
            [
                dev_directory+'/**/.htaccess',
                dev_directory+'web/**/*.*',
                dev_directory+'src/**/*',
                dev_directory+'app/**/*',
                dev_directory+'vendor/**/*',
                '!'+dev_directory+'app/cache/**/*',
                '!'+dev_directory+'app/logs/**/*'
            ],
            {base:'./'+dev_directory}
        )
        .pipe(gulp.dest(prod_directory))
    ;
});

gulp.task('delete-useless-files', ['move'], function() {
    del([prod_directory+'src/**/public/js'], function (err) {});
});

gulp.task('rights', ['move'], shell.task([
  'php '+prod_directory+'app/console cache:clear --env=prod',
  'chown www-data -R '+prod_directory+'app/cache/ '+prod_directory+'app/logs/ && chmod 775 -R '+prod_directory+'app/cache/ '+prod_directory+'app/logs'
]))

/**
 * Informations to minimify scripts
 *  - bundleName: the name of the bundle directory in /web/bundles
 *  - directoryName : the name of the directory containing scripts under /js directory of the bundle
 * @type {*[]}
 */
var jsFormatInfos = [
    {'bundleName': 'ovskilanguage', 'directoryName': 'edition'},
    {'bundleName': 'ovskilanguage', 'directoryName': 'revision'}
];

/**
 * Every format script task name
 *
 * @type {Array}
 */
var scriptsFormatTasksName = [];

jsFormatInfos.forEach(function(info) {
    var taskName = 'scripts-format-'+info.bundleName+'-'+info.directoryName;
    scriptsFormatTasksName.push(taskName);
    gulp.task(taskName, ['delete-useless-files'], function() {
        return gulp.src(dev_directory+'web/bundles/'+info.bundleName+'/js/'+info.directoryName+'/*.js')
            .pipe(concat('./'+info.directoryName+'.min.js'))
            .pipe(uglify({ mangle: {toplevel: true} }))
            .pipe(gulp.dest(prod_directory+'web/bundles/ovskilanguage/js/'+info.directoryName+'/'))
        ;
    });
});

/**
 * Informations to update twig files with scripts inclusions
 *  - bundleName: the name of the bundle directory in /web/bundles
 *  - directoryName : the name of the directory containing scripts under /js directory of the bundle
 * @type {*[]}
 */
var jsIncludeInfos = [
    {'bundleName': 'ovskilanguage', 'directoryName': 'edition', 'fileName': 'src/Ovski/LanguageBundle/Resources/views/Translation/edition.html.twig'},
    {'bundleName': 'ovskilanguage','directoryName': 'revision', 'fileName': 'src/Ovski/LanguageBundle/Resources/views/Translation/revision.html.twig'}
];

/**
 * Every include script task name
 *
 * @type {Array}
 */
var scriptsIncludeTasksName = [];

jsIncludeInfos.forEach(function(info) {
    var taskName = 'scripts-include-'+info.bundleName+'-'+info.directoryName;
    scriptsIncludeTasksName.push(taskName);
    gulp.task(taskName, ['delete-useless-files'], function() {
        return gulp.src(prod_directory+info.fileName, {base: './'})
            .pipe(replace(
                /\{# startscript (([a-z]|\.)*) (.|\n)*endscript #\}/g,
                '<script type="text/javascript" src="/bundles/'+info.bundleName+'/js/'+info.directoryName+'/$1"></script>'
            ))
            .pipe(gulp.dest('./'));
        ;
    });
});

/*******************/
/*** TASK GROUPS ***/
/*******************/

// Script tasks
gulp.task('scripts-format', scriptsFormatTasksName);
gulp.task('scripts-include', scriptsIncludeTasksName);
gulp.task('scripts', ['scripts-format', 'scripts-include']);

// Developments tasks
gulp.task('dev', ['lint', 'watch']);

// Production tasks (package a prod directory)
gulp.task('prod', ['clean', 'move', 'rights'/*, 'delete-useless-files', 'scripts'*/]);
