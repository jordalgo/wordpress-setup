var fs = require('fs');
var gulp = require('gulp');
var path = require('path');
var plugins = require('gulp-load-plugins')();
var buildConf = require('./build-config.json');

function getFolders(dir) {
  return fs.readdirSync(dir)
  .filter(function(file) {
    return fs.statSync(path.join(dir, file)).isDirectory();
  });
}

var THEME_PATH = 'wp-content/themes';
var folders = getFolders(THEME_PATH);
var prodTasks = [];

folders.forEach(function(theme) {
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

