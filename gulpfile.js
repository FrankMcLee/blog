var gulp = require('gulp');
var sass = require('gulp-sass');
// var elixir = require('laravel-elixir');
// elixir(function(mix) {
//     mix.sass('app.scss');
// });

gulp.task('sass', function () {
    return gulp.src('resources/assets/sass/**/*.scss')
        .pipe(sass().on('error', sass.logError))
        .pipe(gulp.dest('public/css'));
});

gulp.task('sass:watch', function () {
    gulp.watch('resources/assets/sass/**/*.scss', ['sass']);
});

gulp.task('script', function(){
    gulp.src('resources/assets/js/**/*.js') // 匹配 'client/js/somedir/somefile.js' 并且将 `base` 解析为 `client/js/`
        .pipe(minify())
        .pipe(gulp.dest('public/js'));  // 写入 'build/somedir/somefile.js'
});

gulp.task('script:watch', function() {
   gulp.watch('resources/assets/js/**/*.js', ['script']);
});


gulp.task('default', [ 'sass:watch', 'script:watch']);