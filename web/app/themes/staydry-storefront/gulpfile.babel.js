'use strict';

import gulp from 'gulp';
import gutil from 'gulp-util';
import svgSprite from 'gulp-svg-sprite';
import svg2png from 'gulp-svg2png';
import size from 'gulp-size';
import runSequence from 'run-sequence';
import sass from 'gulp-sass';
import merge from 'merge-stream';

// See https://github.com/austinpray/asset-builder
var manifest = require('asset-builder')('./assets/manifest.json');

// `path` - Paths to base asset directories. With trailing slashes.
// - `path.source` - Path to the source files. Default: `assets/`
// - `path.dist` - Path to the build directory. Default: `dist/`
var path = manifest.paths;

// `config` - Store arbitrary configuration values here.
var config = manifest.config || {};

// `globs` - These ultimately end up in their respective `gulp.src`.
// - `globs.js` - Array of asset-builder JS dependency objects. Example:
//   ```
//   {type: 'js', name: 'main.js', globs: []}
//   ```
// - `globs.css` - Array of asset-builder CSS dependency objects. Example:
//   ```
//   {type: 'css', name: 'main.css', globs: []}
//   ```
// - `globs.fonts` - Array of font path globs.
// - `globs.images` - Array of image path globs.
// - `globs.bower` - Array of all the main Bower files.
var globs = manifest.globs;

// `project` - paths to first-party assets.
// - `project.js` - Array of first-party JS assets.
// - `project.css` - Array of first-party CSS assets.
var project = manifest.getProjectGlobs();

var basePaths = {
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
        },
        styles: {
            src: basePaths.src + 'styles/**/*.scss',
            dest: basePaths.dest + 'styles/'
        }
    },
    changeEvent = (event) => {
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
gulp.task('svg-sprite', () => {
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
gulp.task('png-sprite', ['svg-sprite'], () => {
    return gulp.src(basePaths.dest + paths.sprite.svg)
        .pipe(svg2png())
        .pipe(size({
            showFiles: true
        }))
        .pipe(gulp.dest(paths.images.dest));
});

gulp.task('sprite', ['png-sprite']);

gulp.task('styles', () => {
    var merged = merge();
    
    manifest.forEachDependency('css', (dep) => {
        merged.add(gulp.src(dep.globs)
            .pipe(sass().on('error', sass.logError)));
    });
    
    return merged
        .pipe(gulp.dest(path.dist + 'styles'));
});

gulp.task('watch', () => {
    gulp.watch(paths.sprite.src, ['sprite']).on('change', function(event) {
	changeEvent(event);
    });
});

gulp.task('default', ['sprite'], function(){
    
});
