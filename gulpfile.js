const fs = require('fs');
const path = require('path');
const gulp = require('gulp');
const browserify = require('browserify');
const babelify = require('babelify');
const sass = require('gulp-sass');
const rename = require('gulp-rename');

gulp.task('build:js', () => {
    browserify('./ui/src/index.js')
        .transform(
            babelify.configure({
                presets: ['env']
            })
        )
        .bundle()
        .pipe(fs.createWriteStream(
            path.join('public', 'dist', 'app.js')
        ))
});

gulp.task('build:sass', () => {
    gulp.src('./ui/src/style/index.scss')
        .pipe(sass().on('error', sass.logError))
        .pipe(rename('app.css'))
        .pipe(gulp.dest('./public/dist'))
});

gulp.task('default', ['build:js', 'build:sass']);