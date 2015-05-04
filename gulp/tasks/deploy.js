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
  gulp.task('deploy', function () {
    var deployFile = '../storage/app/deploy.json';

    readJSON(deployFile, function (err, result) {
      if (err) {
        return console.log(err);
      }

      var deploymentId = result.id;
      var command      = 'php ../artisan webloyer:deploy ' + deploymentId;

      exec(command);
    });
  });

  this.registerWatcher('deploy', '../storage/app/deploy.json');

  return this.queueTask('deploy');
});
