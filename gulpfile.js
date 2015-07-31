var gulp = require('gulp');
var path = require('path');
var plugins = require('gulp-load-plugins')();
var browserifyHandlebars = require('browserify-handlebars');
var browserSync = require('browser-sync');
var argv = require('yargs').argv;

var buildConfig = require('./build-config.json');
var THEME_PATH = buildConfig.themePath;
var THEME_LIBRARY_PATH = THEME_PATH + 'library/';
var baseDestURL = '/';
var LESS_PATH = buildConfig.themePath + buildConfig.lessPath;
var SCRIPT_PATH = buildConfig.themePath + buildConfig.scriptPath;
var BUILD_PATH = buildConfig.themePath + buildConfig.buildPath;

var lessFiles = buildConfig.style.map(function(file) {
  return LESS_PATH + file + '.less';
});
var cssFiles = buildConfig.style.map(function(file) {
  return BUILD_PATH + file + '.css';
});
var scriptFiles = buildConfig.scripts.map(function(file) {
  return SCRIPT_PATH + file;
});
var browserifyFiles = buildConfig.browserify.files.map(function(file) {
  if (buildConfig.es6) {
    return SCRIPT_PATH + 'es5/' + file;
  } else {
    return SCRIPT_PATH + file;
  }
});
var uglifyFiles = buildConfig.browserify.files.map(function(file) {
  return BUILD_PATH + file;
});

// Error Handler
function onError(err) {
  plugins.util.log(err.message);
  this.emit('end');
}

// Less And CSS tasks
gulp.task('less', function() {
  return gulp.src(lessFiles)
    .pipe(plugins.less({
      generateSourceMap: true, // default true
      paths: [ path.join(__dirname, 'less', 'includes') ]
    }))
    .on('error', onError)
    .pipe(gulp.dest(BUILD_PATH))
    ;
});

gulp.task('minify-css', ['less'], function() {
  return gulp.src(cssFiles)
    .pipe(plugins.minifyCss({ processImport: false }))
    .pipe(gulp.dest(BUILD_PATH))
    ;
});

// JS tasks
gulp.task('jshint', function() {
  return gulp.src(scriptFiles)
    .pipe(plugins.jshint('.jshintrc'))
    .pipe(plugins.jshint.reporter('jshint-stylish'))
    .pipe(plugins.jshint.reporter('fail'));
});

gulp.task('babel', function () {
  return gulp.src(scriptFiles)
    .pipe(plugins.babel())
    .on('error', onError)
    .pipe(gulp.dest(SCRIPT_PATH + 'es5/'))
    ;
});

gulp.task('browserify', buildConfig.es6 ? ['babel'] : [], function() {
  return gulp.src(browserifyFiles)
    .pipe(plugins.browserify({
      transform: [browserifyHandlebars],
      shim: buildConfig.browserify.shim
    }))
    .on('error', onError)
    .pipe(gulp.dest(BUILD_PATH))
    ;
});

gulp.task('uglify', ['browserify'], function() {
  return gulp.src(uglifyFiles)
    .pipe(plugins.uglify())
    .pipe(gulp.dest(BUILD_PATH))
    ;
});

// Watch tasks
gulp.task('watch', function() {
  gulp.watch(scriptFiles, ['jshint', 'browserify', browserSync.reload]);
  gulp.watch(LESS_PATH + '**/*.less', ['less', browserSync.reload]);
  gulp.watch(THEME_PATH + '*.php', browserSync.reload);
});

gulp.task('browser-sync', function () {
   browserSync({
      proxy: buildConfig.domain
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
  'rsync -e "ssh -p 2222" -avz --exclude-from "rsync-exclude-list.txt" ./ ' + buildConfig.remote
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

