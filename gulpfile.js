var fs = require('fs');
var gulp = require('gulp');
var path = require('path');
var plugins = require('gulp-load-plugins')();
var buildConf = require('./build-config.json');

var THEME_PATH = 'wp-content/themes';
var activeThemes = buildConf.activeThemes;
var prodTasks = [];

activeThemes.forEach(function(theme) {
  var name = theme + '-prod';
  gulp.task(name, function(){
    return plugins
    .run('npm run prod', { cwd: THEME_PATH + '/' + theme })
    .exec();
  });
  prodTasks.push(name);
});

gulp.task('rsync', function() {
  plugins.run(
  'rsync -e "ssh -p 2222" -avz --exclude-from "rsync-exclude-list.txt" ./ ' + buildConf.remote)
  .exec();
});

gulp.task('deploy', prodTasks, function() { gulp.start('rsync'); } );

