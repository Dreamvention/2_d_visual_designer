/*jslint node: true */
"use strict";

var gulp = require("gulp");
var concat = require("gulp-concat");
var uglify = require("gulp-uglify");
var cleanCSS = require("gulp-clean-css");
var del = require("del");
var sass = require("gulp-sass");
var sourcemaps = require("gulp-sourcemaps");
var autoprefixer = require("gulp-autoprefixer");
var browserSync = require("browser-sync");
var path = require("path");

//script paths
var jsDest = "../dist/";

var sassDest = "../../../theme/default/stylesheet/d_visual_designer/";

var baseDir = path.resolve(__dirname, "../../../../");

gulp.task("clean", function () {
    return del(jsDest + "**", {force: true});
});

gulp.task("copy", ["copy-fonts", "copy-img"], function () {
    gulp.start(["basic-scripts", "secondary-scripts", "basic-styles", "secondary-styles"]);
});

gulp.task("copy-fonts", function () {
    return gulp.src([
        "../library/icon-fonts/fonts/*",
        "../library/fontIconPicker/fonts/*",
        "../library/summernote/fonts/*"
    ])
        .pipe(gulp.dest(jsDest + "fonts/"));
});

gulp.task("copy-img", function () {
    return gulp.src([
        "../library/bootstrap-colorpicker/img/*",
        "../library/select2/img/*"
    ])
        .pipe(gulp.dest(jsDest + "img/"));
});

gulp.task("basic-scripts", function () {
    return gulp.src([
        "../library/sharrre/jquery.sharrre.min.js",
        "../library/magnific/jquery.magnific-popup.min.js",
        "../library/chart/Chart.min.js",
        "../library/pie-chart.js",
        "../library/circle-progress.js",
        "../library/owl-carousel/owl.carousel.min.js",
        "../library/webfont.js",
        "../library/underscore-min.js",
        "../library/fastclone.js"
    ])
        .pipe(concat("vd-basic-libraries.min.js"))
        .pipe(uglify())
        .pipe(gulp.dest(jsDest));
});

gulp.task("secondary-scripts", function () {
    return gulp.src([
        "../library/bootstrap-switch/bootstrap-switch.js",
        "../library/fontIconPicker/iconset.js",
        "../library/fontIconPicker/jquery.fonticonpicker.min.js",
        "../library/bootstrap-colorpicker/bootstrap-colorpicker.min.js",
        "../library/summernote/summernote.min.js",
        "../library/summernote/summernote-cleaner.js",
        "../library/jquery-ui.js",
        "../library/jquery.serializejson.js",
        "../library/select2/select2.full.min.js",
        "../library/fontset.js"
    ])
        .pipe(concat("vd-secondary-libraries.min.js"))
        .pipe(uglify())
        .pipe(gulp.dest(jsDest));
});

gulp.task("basic-styles", function () {
    return gulp.src([
        "../library/sharrre/style.css",
        "../library/magnific/magnific-popup.css",
        "../library/owl-carousel/owl.carousel.css",
        "../library/owl-carousel/owl.transitions.css",
        "../library/animate.css",
        "../library/icon-fonts/elusive-icons.scss",
        "../library/icon-fonts/fontawesome.scss",
        "../library/icon-fonts/ionicons.scss",
        "../library/icon-fonts/map-icons.scss",
        "../library/icon-fonts/material-design-iconic-font.scss",
        "../library/icon-fonts/octicons.scss",
        "../library/icon-fonts/typicons.scss",
        "../library/icon-fonts/weather-icons.scss"
    ])
        .pipe(sass({outputStyle: "compressed"}).on("error", sass.logError))
        .pipe(concat("vd-basic-libraries.min.css"))
        .pipe(cleanCSS())
        .pipe(gulp.dest(jsDest));
});

gulp.task("secondary-styles", function () {
    return gulp.src([
        "../library/owl-carousel/owl.transitions.css",
        "../library/bootstrap-switch/bootstrap-switch.min.css",
        "../library/fontIconPicker/jquery.fonticonpicker.css",
        "../library/fontIconPicker/jquery.fonticonpicker.grey.min.css",
        "../library/bootstrap-colorpicker/bootstrap-colorpicker.min.css",
        "../library/summernote/summernote.css",
        "../library/select2/select2-bootstrap.min.css",
        "../library/select2/select2.font.css",
        "../library/select2/select2.min.css"
    ])
        .pipe(concat("vd-secondary-libraries.min.css"))
        .pipe(cleanCSS())
        .pipe(gulp.dest(jsDest));
});

gulp.task("build_library", ["clean"], function () {
    gulp.start("copy")
});

gulp.task("sass", function () {
    return gulp.src(sassDest + "*.scss")
        .pipe(sourcemaps.init())
        .pipe(sass({outputStyle: "compressed"}).on("error", sass.logError))
        .pipe(autoprefixer({
            browsers: ["last 15 versions"]
        }))
        .pipe(sourcemaps.write("./"))
        .pipe(gulp.dest(sassDest))
        .pipe(browserSync.reload({stream: true}));
});

gulp.task("sass:watch", function () {
    gulp.watch([sassDest + "*.scss", sassDest + "core/*.scss"], ["sass"]);
});

gulp.task("browser_sync_init", function () {
    if (typeof process.env.HOST !== "undefined") {
        browserSync({
            proxy: process.env.HOST
        });
    }
})

gulp.task("build_sass", ["browser_sync_init"], function () {
    if (typeof process.env.HOST !== "undefined") {
        gulp.watch([
            baseDir + "/controller/extension/d_visual_designer/**/*.php",
            baseDir + "/view/theme/default/template/extension/d_visual_designer/**/*.tag"
        ], browserSync.reload);
    }
    gulp.start(["sass", "sass:watch"]);
})