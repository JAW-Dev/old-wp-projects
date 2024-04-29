var postcss = require("gulp-postcss");
var autoprefixer = require("autoprefixer");
var gulp = require("gulp");
var rename = require("gulp-rename");
var cheerio = require("gulp-cheerio");
var notify = require("gulp-notify");

// Sass Plugins
var sass = require("gulp-sass");
var sourcemaps = require("gulp-sourcemaps");

// SVG Plugins
var svgmin = require("gulp-svgmin");
var svgstore = require("gulp-svgstore");

// Minifiy JS
var concat = require("gulp-concat");
var terser = require("gulp-terser");

var processors = [autoprefixer({ grid: true })];

gulp.task(
  "sass",
  gulp.series(function () {
    return gulp
      .src("assets/styles/*.scss")
      .pipe(sourcemaps.init())
      .pipe(
        sass({
          includePaths: require("node-neat").includePaths,
        }).on("error", sass.logError)
      )
      .pipe(postcss(processors))
      .pipe(sourcemaps.write("./assets/maps"))
      .pipe(gulp.dest("./"))
      .pipe(notify({ message: "Regular styles finished." }));
  })
);

gulp.task(
  "editor-sass",
  gulp.series(function () {
    return gulp
      .src("assets/styles/editor-style.scss")
      .pipe(sourcemaps.init())
      .pipe(
        sass({
          includePaths: require("node-neat").includePaths,
        }).on("error", sass.logError)
      )
      .pipe(postcss(processors))
      .pipe(sourcemaps.write())
      .pipe(gulp.dest("./"))
      .pipe(notify({ message: "Editor styles finished." }));
  })
);

gulp.task(
  "svgstore",
  gulp.series(function () {
    return gulp
      .src("assets/icons/src/*.svg")
      .pipe(rename({ prefix: "ico-" }))
      .pipe(svgmin())
      .pipe(svgstore())
      .pipe(
        cheerio({
          run: function ($) {
            $("[fill]").removeAttr("fill");
          },
          parserOptions: { xmlMode: true },
        })
      )
      .pipe(gulp.dest("assets/icons/dist/"))
      .pipe(notify({ message: "SVG proccess finished." }));
  })
);

//script paths
var jsFiles = [
    "assets/js/cookies.js",
    "assets/js/typekit.js",
    "assets/js/mobile-menu.js",
    "assets/js/sitewide.js",
    "assets/js/nps.js",
    "!assets/js/build/*.js",
  ],
  jsDest = "assets/js/build/";

gulp.task(
  "scripts",
  gulp.series(function () {
    return gulp
      .src(jsFiles)
      .pipe(concat("site-wide.js"))
      .pipe(gulp.dest(jsDest))
      .pipe(rename("site-wide.min.js"))
      .pipe(
        terser({
          ecma: 5,
        })
      )
      .pipe(gulp.dest(jsDest))
      .pipe(notify({ message: "Scripts finished." }));
  })
);

gulp.task(
  "watch",
  gulp.series(function () {
    gulp.watch("./assets/styles/**/*.scss", gulp.series("sass"));
    gulp.watch("assets/styles/editor-style.scss", gulp.series("editor-sass"));
    gulp.watch("./assets/icons/src/*.svg", gulp.series("svgstore"));
    gulp.watch(
      ["assets/js/**/*.js", "!assets/js/build/*.js"],
      gulp.series("scripts")
    );
  })
);

gulp.task(
  "default",
  gulp.series("sass", "editor-sass", "svgstore", "scripts", "watch")
);
