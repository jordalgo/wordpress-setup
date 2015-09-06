var spawn = require('child_process').spawn;
var buildConf = require('./build-config.json');
var THEMES_FOLDER = 'wp-content/themes/';
var themeCount = buildConf.activeThemes.length;

function print(data) {
  console.log('' + data);
}

function complete(code) {
  console.log('child process exited with code ' + code);
}

function rsync() {
  console.log('Starting rsync...');
  var r = spawn(
    'rsync',
    [
      '-e',
      'ssh',
      '-p',
      '2222',
      '-avz',
      '--exclude-from',
      'rsync-exclude-list.txt',
      './',
      buildConf.remote
    ]
  );

  r.stdout.pipe(process.stdout);
  r.stderr.pipe(process.stdout);
  r.on('close', complete);
}

function prod(theme) {
  var p = spawn('npm', ['run', 'prod'], { cwd: 'wp-content/themes/' + theme });
  p.stdout.pipe(process.stdout);
  p.stderr.pipe(process.stdout);
  p.on('close', function(code) {
    complete(code);
    themeCount--;
    if (themeCount === 0) {
      rsync();
    }
  });
}

buildConf.activeThemes.forEach(prod);

