var gulp = require('gulp')
  , path = require('path')
  , plugins = require('gulp-load-plugins')()
  , browserifyHandlebars = require('browserify-handlebars')
  , browserSync = require('browser-sync')

  , siteConfig = require('./site-config.json'),
  , themePath = siteConfig.themePath,
  , themePathLibrary = themePath + 'library/',
  , baseDestURL = '/'
  , CSS_PATH = './library/style/css/'
  , SCRIPT_PATH = './library/scripts/'

  , argv = require('yargs').argv
  ;

gulp.task('less', function() {
  return gulp.src('./library/style/less/style.less')
    .pipe(plugins.less({
      generateSourceMap: true, // default true
      paths: [ path.join(__dirname, 'less', 'includes') ]
    }))
    .pipe(gulp.dest(CSS_PATH))
    ;
});

gulp.task('minify-css', ['less'], function() {
  return gulp.src(CSS_PATH + 'style.css')
    .pipe(plugins.minifyCss())
    .pipe(gulp.dest(CSS_PATH))
    ;
});

gulp.task('jshint', function() {
  return gulp.src(SCRIPT_PATH + 'modules/**/*.js')
    .pipe(plugins.jshint('.jshintrc'))
    .pipe(plugins.jshint.reporter('jshint-stylish'))
    .pipe(plugins.jshint.reporter('fail'));
});

gulp.task('e6to5', function () {
  return gulp.src(SCRIPT_PATH + 'modules/*.js')
    .pipe(plugins['6to5']())
    .pipe(gulp.dest(SCRIPT_PATH + 'es5/'))
    ;
});

gulp.task('browserify', ['e6to5'], function() {
  return gulp.src(SCRIPT_PATH + 'es5/main.js')
    .pipe(plugins.browserify({
      transform: [browserifyHandlebars],
      shim: {
        jQuery: {
          path: 'library/scripts/vendor/jquery.min.js',
          exports: '$'
        }
      }
    }))
    .pipe(gulp.dest(SCRIPT_PATH + 'build/'))
    ;
});

gulp.task('uglify', ['browserify'], function() {
  return gulp.src(SCRIPT_PATH + 'build/main.js')
    .pipe(plugins.uglify())
    .pipe(gulp.dest(SCRIPT_PATH + 'build/'))
    ;
});

gulp.task('watch', function() {
  gulp.watch(SCRIPT_PATH + 'modules/*.js', ['jshint', 'browserify', browserSync.reload]);
  gulp.watch('./library/style/less/**/*.less', ['less', browserSync.reload]);
  gulp.watch(SCRIPT_PATH + 'templates/*.hbs', ['browserify', browserSync.reload]);
  gulp.watch('./index.html', [browserSync.reload]);
});

gulp.task('browser-sync', function () {
   browserSync({
      server: {
         baseDir: './'
      }
   });
});

gulp.task('git-commit', function(){
  return gulp.src('.')
    .pipe(plugins.git.add({args: '--all'}))
    .pipe(plugins.git.commit(argv.commit))
    ;
});

gulp.task('git-push', ['git-commit'], function(done){
  plugins.git.push('all', 'master', function(err){
    if (err) { console.log(err); }
    done();
  });
});

gulp.task(
  'default',
  [
    'less'
    , 'jshint'
    , 'browserify'
    , 'watch'
    , 'browser-sync'
  ]
);

// run this task with a commit message gulp deploy --commit="commit message"
gulp.task(
  'deploy',
  [
    'minify-css'
    , 'jshint'
    , 'uglify'
  ],
  function() {
    gulp.start('git-push');
  }
);

