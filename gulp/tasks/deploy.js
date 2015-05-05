var gulp   = require('gulp');
var elixir = require('laravel-elixir');
var exec   = require('child_process').exec;
var fs     = require('fs');

function readJSON(path, callback) {
  fs.readFile(path, 'utf8', function (err, data) {
    if (err) {
      return callback(err);
    }

    try {
      callback(null, JSON.parse(data));
    } catch (e) {
      callback(e);
    }
  });
}

elixir.extend('deploy', function () {
  var deployFile = '../storage/app/deploy.json';

  gulp.task('deploy', function () {
    readJSON(deployFile, function (err, result) {
      if (err) {
        return console.log(err);
      }

      var deploymentId = result.id;
      var command      = 'php ../artisan webloyer:deploy ' + deploymentId;

      exec(command);
    });
  });

  // If file does not exist, then create an empty file
  if (!fs.existsSync(deployFile)) {
    fs.closeSync(fs.openSync(deployFile, 'w'));
    fs.chmodSync(deployFile, 0777);
  }

  this.registerWatcher('deploy', deployFile);

  return this.queueTask('deploy');
});
