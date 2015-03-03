var gulp = require('gulp')
  , path = require('path')
  , plugins = require('gulp-load-plugins')()
  , browserifyHandlebars = require('browserify-handlebars')
  , browserSync = require('browser-sync')

  , siteConfig = require('./site-config.json')
  , themePath = siteConfig.themePath
  , themePathLibrary = themePath + 'library/'
  , baseDestURL = '/'
  , CSS_PATH = './library/style/css/'
  , SCRIPT_PATH = './library/scripts/'

  , argv = require('yargs').argv
  ;

// Less And CSS tasks
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

// Watch tasks
gulp.task('watch', function() {
  gulp.watch(SCRIPT_PATH + 'modules/*.js', ['jshint', 'browserify', browserSync.reload]);
  gulp.watch('./library/style/less/**/*.less', ['less', browserSync.reload]);
  //gulp.watch(SCRIPT_PATH + 'templates/*.hbs', ['browserify', browserSync.reload]);
  gulp.watch('./wordpress/**/*.php', [browserSync.reload]);
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
  ],
  function() {
    gulp.start('git-push');
  }
);

