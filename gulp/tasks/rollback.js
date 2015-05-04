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

elixir.extend('rollback', function () {
  gulp.task('rollback', function () {
    var rollbackFile = '../storage/app/rollback.json';

    readJSON(rollbackFile, function (err, result) {
      if (err) {
        return console.log(err);
      }

      var deploymentId = result.id;
      var command      = 'php ../artisan webloyer:rollback ' + deploymentId;

      exec(command);
    });
  });

  this.registerWatcher('rollback', '../storage/app/rollback.json');

  return this.queueTask('rollback');
});
