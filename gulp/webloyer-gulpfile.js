var elixir = require('laravel-elixir');

require('./tasks/deploy');
require('./tasks/rollback');

elixir(function(mix) {
  mix.deploy();
});

elixir(function(mix) {
  mix.rollback();
});
