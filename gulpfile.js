var gulp = require('gulp')
  , path = require('path')
  , plugins = require('gulp-load-plugins')()
  , browserifyHandlebars = require('browserify-handlebars')
  , browserSync = require('browser-sync')

  , siteConfig = require('./site-config.json')
  , THEME_PATH = siteConfig.themePath
  , THEME_LIBRARY_PATH = THEME_PATH + 'library/'
  , baseDestURL = '/'
  , LESS_PATH = THEME_LIBRARY_PATH + 'less/'
  , SCRIPT_PATH = THEME_LIBRARY_PATH + 'js/'
  , BUILD_PATH = THEME_LIBRARY_PATH + 'build/'

  , argv = require('yargs').argv
  ;

// Less And CSS tasks
gulp.task('less', function() {
  return gulp.src([LESS_PATH + 'style.less', LESS_PATH + 'style-base.less'])
    .pipe(plugins.less({
      generateSourceMap: true, // default true
      paths: [ path.join(__dirname, 'less', 'includes') ]
    }))
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
          path: SCRIPT_PATH + 'vendor/jquery.min.js',
          exports: '$'
        }
      }
    }))
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
  gulp.watch(LESS_PATH + '*.less', ['less', browserSync.reload]);
  //gulp.watch(SCRIPT_PATH + 'templates/*.hbs', ['browserify', browserSync.reload]);
  gulp.watch(THEME_PATH + '*.php', [browserSync.reload]);
  gulp.watch('./wp-content/wp-config.php', ['db-replace-local', browserSync.reload]);
});

gulp.task('browser-sync', function () {
   browserSync({
      server: {
         baseDir: './'
      }
   });
});

// Database Replacement
function dbReplace(db) {
  return gulp.src(['./wp-content/wp-config.php'])
    .pipe(plugins.replace(/@@dbname/g, db.name))
    .pipe(plugins.replace(/@@dbuser/g, db.user))
    .pipe(plugins.replace(/@@dbpassword/g, db.password))
    .pipe(plugins.replace(/@@dbhost/g, db.host))
    .pipe(gulp.dest('.'));
}

gulp.task('db-replace-local', function(){
  var db = siteConfig.dbConfig.local;
  return dbReplace(db);
});

gulp.task('db-replace-remote', function(){
  var db = siteConfig.dbConfig.remote;
  return dbReplace(db);
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
gulp.task('rsync', function() {
  gulp.src('./')
    .pipe(plugins.rsync({
      hostname: 'example.com',
      destination: '/public_html',
      progress: true,
      exclude: ['node_modules/', '.git/', 'wp-content/uploads/']
    }));
});

gulp.task(
  'default',
  [
    'less'
    , 'jshint'
    , 'browserify'
    , 'db-replace-remote'
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
    , 'db-replace-remote'
    , 'rsync'
  ],
  function() {
    gulp.start('git-push');
  }
);

