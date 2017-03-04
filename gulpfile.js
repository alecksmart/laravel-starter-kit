var elixir = require('laravel-elixir');
var gutils = require('gulp-util');
var  b = elixir.config.js.browserify;

if(gutils.env._.indexOf('watch') > -1){
    b.plugins.push({
        name: "browserify-hmr",
        options : {}
    });
}

b.transformers.push({
    name: "vueify",
    options : {}
});

elixir(function(mix) {
    mix.sass('app.scss')
        .browserify('app.js')
        .version(['css/app.css', 'js/app.js']);

});
