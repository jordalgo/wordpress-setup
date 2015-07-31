var gulp = require('gulp')
  , path = require('path')
  , plugins = require('gulp-load-plugins')()
  , browserifyHandlebars = require('browserify-handlebars')
  , browserSync = require('browser-sync')

  , siteConfig = require('./package.json')
  , THEME_PATH = siteConfig.themePath
  , THEME_LIBRARY_PATH = THEME_PATH + 'library/'
  , baseDestURL = '/'
  , LESS_PATH = THEME_LIBRARY_PATH + 'less/'
  , SCRIPT_PATH = THEME_LIBRARY_PATH + 'js/'
  , BUILD_PATH = THEME_LIBRARY_PATH + 'build/'

  , argv = require('yargs').argv
  ;

// Error Handler
function onError(err) {
  plugins.util.log(err.message);
  this.emit('end');
}

// Less And CSS tasks
gulp.task('less', function() {
  return gulp.src([LESS_PATH + 'style.less', LESS_PATH + 'style-base.less'])
    .pipe(plugins.less({
      generateSourceMap: true, // default true
      paths: [ path.join(__dirname, 'less', 'includes') ]
    }))
    .on('error', onError)
    .pipe(gulp.dest(BUILD_PATH))
    ;
});

gulp.task('minify-css', ['less'], function() {
  return gulp.src([BUILD_PATH + 'style.css', BUILD_PATH + 'style-base.css'])
    .pipe(plugins.minifyCss())
    .pipe(gulp.dest(BUILD_PATH))
    ;
});

// JS tasks
gulp.task('jshint', function() {
  return gulp.src(SCRIPT_PATH + 'modules/*.js')
    .pipe(plugins.jshint('.jshintrc'))
    .pipe(plugins.jshint.reporter('jshint-stylish'))
    .pipe(plugins.jshint.reporter('fail'));
});

gulp.task('babel', function () {
  return gulp.src(SCRIPT_PATH + 'modules/*.js')
    .pipe(plugins.babel())
    .on('error', onError)
    .pipe(gulp.dest(SCRIPT_PATH + 'es5/'))
    ;
});

gulp.task('browserify', ['babel'], function() {
  return gulp.src(SCRIPT_PATH + 'es5/main.js')
    .pipe(plugins.browserify({
      transform: [browserifyHandlebars],
      shim: {
        jQuery: {
          path: SCRIPT_PATH + 'vendor/jquery.min.js',
          exports: '$'
        }
      }
    }))
    .on('error', onError)
    .pipe(gulp.dest(BUILD_PATH))
    ;
});

gulp.task('uglify', ['browserify'], function() {
  return gulp.src(THEME_LIBRARY_PATH + 'build/main.js')
    .pipe(plugins.uglify())
    .pipe(gulp.dest(BUILD_PATH))
    ;
});

// Watch tasks
gulp.task('watch', function() {
  gulp.watch(SCRIPT_PATH + 'modules/*.js', ['jshint', 'browserify', browserSync.reload]);
  gulp.watch(LESS_PATH + '**/*.less', ['less', browserSync.reload]);
  gulp.watch(THEME_PATH + '*.php', browserSync.reload);
});

gulp.task('browser-sync', function () {
   browserSync({
      proxy: "www.testsite.com"
   });
});

// Git Stuff
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

// Rsync
gulp.task('rsync', plugins.shell.task([
  'rsync -e "ssh -p 2222" -avz --exclude-from "rsync-exclude-list.txt" ./ USER@SERVER-IP:FOLDER'
]));

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
    gulp.start('rsync');
  }
);

