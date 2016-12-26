var gulp = require('gulp'),
    del = require('del'),
    pngquant = require('imagemin-pngquant'),
    gulpLoadPlugins = require('gulp-load-plugins'),
    plugins = gulpLoadPlugins();

var path = {
    build: {
        js: 'web/js/',
        css: 'web/css/',
        img: 'web/images/',
        fonts: 'web/fonts/'
    },
    src: {
        js: 'web-src/js/*.js',
        style: 'web-src/less/*',
        img: 'web-src/images/*',
        fonts: 'web-src/fonts/*'
    },
    clean: {
        js: 'web/js/**',
        css: 'web/css/**',
        img: 'web/images/**',
        fonts: 'web/fonts/**'
    }
};

var clean = path.clean;

gulp.task('common-js', function() {
    return gulp.src([
        'bower_components/jquery/dist/jquery.js',
        'bower_components/bootstrap/dist/js/bootstrap.js'
               ])
           .pipe(plugins.sourcemaps.init())
           .pipe(plugins.concat('app.js'))
           .pipe(plugins.uglify())
           .pipe(plugins.sourcemaps.write())
           .pipe(gulp.dest(path.build.js));
    });

gulp.task('js', function () {
    return gulp.src(path.src.js)
        .pipe(plugins.sourcemaps.init())
        .pipe(plugins.uglify())
        .pipe(plugins.sourcemaps.write())
        .pipe(gulp.dest(path.build.js))
});

gulp.task('less', function(){
    return gulp.src(path.src.style)
        .pipe(plugins.sourcemaps.init())
        .pipe(plugins.less())
        .pipe(plugins.csso())
        .pipe(plugins.sourcemaps.write())
        .pipe(gulp.dest(path.build.css))
});

gulp.task('image', function () {
    return gulp.src(path.src.img)
        .pipe(plugins.imagemin({
            progressive: true,
            svgoPlugins: [{removeViewBox: false}],
            use: [pngquant()],
            interlaced: true
        }))
        .pipe(gulp.dest(path.build.img))
});

gulp.task('fonts', function() {
    return gulp.src(path.src.fonts)
        .pipe(gulp.dest(path.build.fonts))
});

gulp.task('build', ['common-js','less','fonts','image', 'js']);

gulp.task('watch', function(){
    gulp.watch(path.src.js, ['js']);
    gulp.watch(path.src.style, ['less']);
    gulp.watch(path.src.img, ['image']);
    gulp.watch(path.src.fonts, ['fonts']);
});

gulp.task('clean', function () {
        del(path.clean.js);
        del(path.clean.fonts);
        del(path.clean.css);
        del(path.clean.img);
    });

gulp.task('default', ['clean', 'build', 'watch']);

