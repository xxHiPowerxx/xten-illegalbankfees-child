// Defining requirements
var gulp = require("gulp");
var plumber = require("gulp-plumber");
var sass = require("gulp-sass");
var rename = require("gulp-rename");
var concat = require("gulp-concat");
var uglify = require("gulp-uglify");
var image = require("gulp-image");
var ignore = require("gulp-ignore");
var rimraf = require("gulp-rimraf");
var sourcemaps = require("gulp-sourcemaps");
var browserSync = require("browser-sync").create();
var del = require("del");
var cleanCSS = require("gulp-clean-css");
var replace = require("gulp-replace");
var autoprefixer = require("gulp-autoprefixer");
const babel = require("gulp-babel");

// Configuration file to keep your code DRY
var cfg = require("./gulpconfig.json");
var paths = cfg.paths;

// Run:
// gulp sass
// Compiles SCSS files in CSS
gulp.task("sass", function() {
	var stream = gulp
		.src(`${paths.sass}/*.scss`)
		.pipe(sourcemaps.init({ loadMaps: true }))
		.pipe(
			plumber({
				errorHandler: function(err) {
					console.log(err);
					this.emit("end");
				}
			})
		)
		.pipe(sass({ errLogToConsole: true }))
		.pipe(autoprefixer("last 2 versions"))
		.pipe(sourcemaps.write("./"))
		.pipe(gulp.dest(paths.css));
	return stream;
});

// Run:
// gulp watch
// Starts watcher. Watcher runs gulp sass task on changes
gulp.task("watch", function() {
	gulp.watch(`${paths.sass}/**/*.scss`, gulp.series("styles"));
	gulp.watch(`${paths.es6}/**/*.js`, gulp.series("scripts"));
	gulp.watch(`${paths.imgsrc}/**/*.**`, gulp.series("imagemin-watch"));
});

// Run:
// gulp imagemin
// Running image optimizing task
gulp.task("imagemin", function(done) {
	return gulp
		.src(`${paths.imgsrc}/**`)
		.pipe(image())
		.pipe(gulp.dest(paths.img));
});

/**
 * Ensures the 'imagemin' task is complete before reloading browsers
 * @verbose
 */
gulp.task(
	"imagemin-watch",
	gulp.series("imagemin", function reloadBrowserSync() {
		browserSync.reload();
	})
);

// Run:
// gulp cssnano
// Minifies CSS files
gulp.task("minifycss", function() {
	return gulp
		.src([paths.css + "/*.css", "!" + paths.css + "/**.min.css"], {
			allowEmpty: true
		})
		.pipe(sourcemaps.init({ loadMaps: true }))
		.pipe(cleanCSS({ compatibility: "*" }))
		.pipe(
			plumber({
				errorHandler: function(err) {
					console.log(err);
					this.emit("end");
				}
			})
		)
		.pipe(rename({ suffix: ".min" }))
		.pipe(sourcemaps.write("./"))
		.pipe(gulp.dest(paths.css));
});

gulp.task("cleancss", function() {
	return gulp
		.src(`${paths.css}/*.min.css`, { read: false, allowEmpty: true }) // Much faster
		.pipe(ignore("child-theme.css"))
		.pipe(rimraf());
});

gulp.task("styles", gulp.series("sass", "minifycss"));

// Run:
// gulp browser-sync
// Starts browser-sync task for starting the server.
gulp.task("browser-sync", function() {
	browserSync.init(cfg.browserSyncWatchFiles, cfg.browserSyncOptions);
});

// Run:
// gulp scripts.
// Uglifies and concat all JS files into one
gulp.task("scripts", function() {
	var scripts = [`${paths.es6}/child-theme.js`];

	gulp
		.src(scripts, { allowEmpty: true })
		.pipe(plumber())
		.pipe(
			babel({
				presets: [
					[
						"@babel/env",
						{
							modules: false
						}
					]
				]
			})
		)
		.pipe(concat("child-theme.min.js"))
		.pipe(uglify())
		.pipe(gulp.dest(paths.js));

	return gulp
		.src(scripts, { allowEmpty: true })
		.pipe(plumber())
		.pipe(
			babel({
				presets: [
					[
						"@babel/env",
						{
							modules: false
						}
					]
				]
			})
		)
		.pipe(concat("child-theme.js"))
		.pipe(gulp.dest(paths.js));
});

// Run:
// gulp watch-bs
// Starts watcher with browser-sync. Browser-sync reloads page automatically on your browser
gulp.task("watch", gulp.parallel("watch", "scripts", "styles", "imagemin"));
gulp.task(
	"watch-bs",
	gulp.parallel("browser-sync", "watch", "scripts", "styles", "imagemin")
);

// Run:
// Deleting any file inside the /dist folder
gulp.task("clean-dist", function() {
	return del([`${paths.dist}/**`]);
});

// Deleting any file inside the /src folder
gulp.task("clean-assets", function() {
	return del(["assets/**/*"]);
});

// Run
// gulp dist
// Copies the files to the /dist folder for distribution as simple theme
gulp.task(
	"create-dist",
	gulp.series("clean-dist", function copyToDistFolder() {
		const ignorePaths = [
				`!${paths.node}`,
				`!${paths.dev}`,
				`!${paths.dist}`,
				`!${paths.dist}/**`
			],
			ignoreFiles = [
				"!package.json",
				"!package-lock.json",
				"!gulpfile.js",
				"!gulpconfig.json",
				"!jshintignore",
				"!.gitignore"
			];

		console.log({ ignorePaths, ignoreFiles });

		return gulp
			.src(["**/*", "assets/**/**.*", "*", ...ignorePaths, ...ignoreFiles], {
				buffer: false
			})
			.pipe(gulp.dest(paths.dist));
	})
);

// Build project
gulp.task(
	"build",
	gulp.series(
		"clean-assets",
		"imagemin",
		gulp.series("styles", "scripts", "create-dist")
	)
);
