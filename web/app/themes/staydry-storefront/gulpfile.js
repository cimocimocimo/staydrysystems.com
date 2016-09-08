var gulp = require('gulp'),
    gutil = require('gulp-util'),
    svgSprite = require('gulp-svg-sprite'),
    svg2png = require('gulp-svg2png'),
    size = require('gulp-size'),
    basePaths = {
        src: 'src/',
        dest: 'assets/'
    },
    paths = {
        images: {
            src: basePaths.src + 'images/',
            dest: basePaths.dest + 'images/'
        },
        sprite: {
            src: basePaths.src + 'sprite/*',
            svg: 'images/sprite.svg',
            css: '../../' + basePaths.src + 'styles/src/_sprite.scss'
        },
        templates: {
            src: basePaths.src + 'templates/'
        }
    },
    changeEvent = function(event) {
        gutil.log(
            'File',
            gutil.colors.cyan(
                event.path.replace(
                    new RegExp('/.*(?=/' + basePaths.src + ')/'),
                    ''
                )
            ),
            'was',
            gutil.colors.magenta(event.type)
        );
    };

// create svg sprite
gulp.task('svg-sprite', function(){
    return gulp.src(paths.sprite.src)
        .pipe(svgSprite({
            shape: {
                spacing: {
                    padding: 5
                }
            },
            mode: {
                css: {
                    dest: './',
                    layout: 'diagonal',
                    sprite: paths.sprite.svg,
                    bust: false,
                    render: {
                        scss: {
                            dest: 'css/src/_sprite.scss',
                            template: 'src/templates/sprite-template.scss'
                        }
                    }
                }
            },
            variables: {
                mapname: 'icons'
            }
        }))
        .pipe(gulp.dest(basePaths.dest));
});

// create png sprite
gulp.task('png-sprite', ['svg-sprite'], function(){
    return gulp.src(basePaths.dest + paths.sprite.svg)
        .pipe(svg2png())
        .pipe(size({
            showFiles: true
        }))
        .pipe(gulp.dest(paths.images.dest));
});

gulp.task('sprite', ['png-sprite']);

gulp.task('watch', function(){
    gulp.watch(paths.sprite.src, ['sprite']).on('change', function(event) {
	changeEvent(event);
    });
});

gulp.task('default', ['sprite']);
