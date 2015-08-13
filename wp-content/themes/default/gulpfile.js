var gulp = require('gulp');
var path = require('path');
var plugins = require('gulp-load-plugins')();
var browserifyHandlebars = require('browserify-handlebars');
var browserSync = require('browser-sync');
var argv = require('yargs').argv;
var buildConf = require('../../../build-config.json');

// Error Handler
function onError(err) {
  plugins.util.log(err.message);
  this.emit('end');
}

// Less And CSS tasks
gulp.task('less', function() {
  return gulp.src(['library/less/style.less', 'library/less/style-base.less'])
    .pipe(plugins.less({
      generateSourceMap: true, // default true
      paths: [ path.join(__dirname, 'less', 'includes') ]
    }))
    .on('error', onError)
    .pipe(gulp.dest('library/build/'))
    ;
});

gulp.task('minify-css', ['less'], function() {
  return gulp.src(['library/build/style.css', 'library/build/style-base.css'])
    .pipe(plugins.minifyCss({ processImport: false }))
    .pipe(gulp.dest('library/build/'))
    ;
});

// JS tasks
gulp.task('jshint', function() {
  return gulp.src('library/js/modules/**/*.js')
    .pipe(plugins.jshint('.jshintrc'))
    .pipe(plugins.jshint.reporter('jshint-stylish'))
    .pipe(plugins.jshint.reporter('fail'));
});

gulp.task('babel', function () {
  return gulp.src('library/js/modules/**/*.js')
    .pipe(plugins.babel())
    .on('error', onError)
    .pipe(gulp.dest('library/js/es5/'))
    ;
});

gulp.task('browserify', ['babel'], function() {
  return gulp.src('library/js/es5/main.js')
    .pipe(plugins.browserify({
      transform: [browserifyHandlebars],
      shim: {
        jQuery: {
          path: 'library/js/vendor/jquery.min.js',
          exports: '$'
        }
      }
    }))
    .on('error', onError)
    .pipe(gulp.dest('library/build/'))
    ;
});

gulp.task('uglify', ['browserify'], function() {
  return gulp.src('library/build/main.js')
    .pipe(plugins.uglify())
    .pipe(gulp.dest('library/build/'))
    ;
});

// Watch tasks
gulp.task('watch', function() {
  gulp.watch('library/js/modules/**/*.js', ['jshint', 'browserify', browserSync.reload]);
  gulp.watch('library/less/**/*.less', ['less', browserSync.reload]);
  gulp.watch('*.php', browserSync.reload);
});

gulp.task('browser-sync', function () {
   browserSync({
      proxy: buildConf.domain
   });
});

gulp.task('default', ['less', 'jshint', 'browserify', 'watch', 'browser-sync']);
gulp.task('prod', ['minify-css', 'jshint', 'uglify']);

