var gulp = require('gulp');
var useref = require('gulp-useref');
var uglify = require('gulp-uglify');
var gulpIf = require('gulp-if');
var pleeease = require('gulp-pleeease');
var del = require('del');
var runSequence = require('run-sequence');
var gutil = require( 'gulp-util' );

var PleeeaseOptions = {
    "autoprefixer": {"browsers": ["last 4 versions", "ios 6"]},
    "rem": true,
    "minifier": true
};

// gulp.task('clean:dist', function() {
//     return del.sync('dist');
// });

gulp.task('clean:dist', function () {
    return del([
      'dist/**/*'
    ]);
  });

gulp.task('images', function(){
    return gulp.src('_site/img/**/*.+(png|jpg|gif|jpeg|svg|GIF|JPG|PNG|JPEG)')
    .pipe(gulp.dest('dist/img'))
});

gulp.task('css_images', function(){
    return gulp.src('_site/css/**/*.+(png|jpg|gif|jpeg|svg|GIF|JPG|PNG|JPEG|eot|ttf|woff|woff2)')
    .pipe(gulp.dest('dist/css'))
});

gulp.task('root_files', function(){
    return gulp.src('_site/*.+(png|jpg|gif|jpeg|svg|GIF|JPG|PNG|JPEG|xml|json|txt|ico|webmanifest)')
    .pipe(gulp.dest('dist'))
});

gulp.task('useref', function(){
    return gulp.src('_site/*.html')
    .pipe(useref())
    .pipe(gulpIf('*.js', uglify()))
    .pipe(gulpIf('*.css', pleeease(PleeeaseOptions)))
    .pipe(gulp.dest('dist'))
});

gulp.task('css', function () {
    gulp.src('_site/css/main.css')
        .pipe(pleeease(PleeeaseOptions))
        .pipe(gulp.dest('dist/css'));
});

gulp.task('js', function () {
    gulp.src('_site/js/scripts.js')
        .pipe(uglify()) 
        .pipe(gulp.dest('dist/js'));
});

gulp.task('js_libs', function () {
    gulp.src('_site/js/libs/*.js')
        .pipe(uglify()) 
        .pipe(gulp.dest('dist/js/libs'));
});


gulp.task('markup', function(){
    return gulp.src('_site/**/*.+(html|htm)')
    .pipe(gulp.dest('dist'))
});

gulp.task('php', function(){
    return gulp.src('_site/*.php')
    .pipe(gulp.dest('dist'))
});

// gulp.task('build', function (callback) {
//     runSequence('clean:dist', 
//         ['useref', 'root_files', 'php', 'images'],
//         callback
//     )
// });

gulp.task('build', function (callback) {
    runSequence('clean:dist', 
        ['markup', 'css', 'js','js_libs', 'root_files', 'php', 'images', 'css_images'],
        callback
    )
});
