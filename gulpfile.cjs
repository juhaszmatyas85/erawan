const gulp = require('gulp');
const concat = require('gulp-concat');
const minify = require('gulp-minify-css');
const sass = require('gulp-sass')(require('sass'));
const strip = require('gulp-strip-css-comments');
const rename = require('gulp-rename');
const copy = require('gulp-copy');
const newer = require('gulp-newer');
const postcss = require('gulp-postcss');
const autoprefixer = require('autoprefixer');
const cssnano = require('cssnano');
const babel = require('gulp-babel');
const sourcemaps = require('gulp-sourcemaps');
const browserSync = require('browser-sync').create();
const fs = require('fs');
const ftp = require('vinyl-ftp');
const through2 = require('through2');
const { exec } = require('child_process');

require('dotenv').config();

const themeDir = 'erawanpgnd.myplan.hu/wp-content/themes/generatepress_child';

const paths = {
    theme: themeDir,
    styles: {
        src: 'src/scss',
        dest: themeDir,
        vendor: {
            src: 'src/css/vendor',
            dest: themeDir + '/css/vendor',
        },
    },
    scripts: {
        src: {
            admin: 'src/js/admin',
            public: 'src/js/public',
            app: 'src/js/app',
            vendor: 'src/js/vendor',
        },
        dest: {
            admin: themeDir + '/admin/js',
            public: themeDir + '/js',
            app: themeDir + '/js',
            vendor: themeDir + '/js/vendor',
        },
    },
};

const babelOptions = {
    presets: ['@babel/preset-env'],
};

function logFileOperation(title) {
    return through2.obj(function (file, enc, cb) {
        console.log(`${title}: ${file.relative}`);
        cb(null, file);
    });
}

function adminScripts() {
    return gulp
        .src(paths.scripts.src.admin + '/**/*.js')
        .pipe(sourcemaps.init())
        .pipe(babel(babelOptions))
        .pipe(sourcemaps.write('.'))
        .pipe(gulp.dest(paths.scripts.dest.admin))
        .pipe(browserSync.stream());
}

function publicScripts() {
    return (
        gulp
            .src(paths.scripts.src.public + '/**/*.js', { since: gulp.lastRun(publicScripts) })
            .pipe(sourcemaps.init())
            // .pipe(babel(babelOptions))
            .pipe(sourcemaps.write('.'))
            .pipe(gulp.dest(paths.scripts.dest.public))
            .pipe(browserSync.stream())
    );
}

function appScripts(done) {
    exec('npx vite build', (err, stdout, stderr) => {
        if (err) {
            console.error(stderr);
            done(err);
        } else {
            console.log(stdout);
            browserSync.reload();
            done();
        }
    });
}

function vendorScripts() {
    return gulp
        .src(paths.scripts.src.vendor + '/**/*.js')
        .pipe(gulp.dest(paths.scripts.dest.vendor))
        .pipe(browserSync.stream());
}

function adminStyles() {
    const task = gulp
        .src(paths.styles.src + '/Admin.scss')
        .pipe(sourcemaps.init())
        .pipe(sass().on('error', sass.logError));

    return task
        .pipe(postcss([autoprefixer(), cssnano()]))
        .pipe(rename('admin.css'))
        .pipe(sourcemaps.write('.'))
        .pipe(gulp.dest(paths.theme + '/admin/css'))
        .pipe(browserSync.stream());
}

function styles() {
    const task = gulp
        .src(paths.styles.src + '/Main.scss')
        .pipe(sourcemaps.init())
        .pipe(sass().on('error', sass.logError));

    return task
        .pipe(postcss([autoprefixer(), cssnano()]))
        .pipe(rename('erawan.css'))
        .pipe(sourcemaps.write('.'))
        .pipe(gulp.dest(paths.styles.dest))
        .pipe(browserSync.stream());
}

function copyVendorFiles() {
    return gulp
        .src(paths.styles.vendor.src + '/**/*')
        .pipe(newer(paths.styles.vendor.dest))
        .pipe(gulp.dest(paths.styles.vendor.dest));
}

function watchFiles() {
    // Scripts
    gulp.watch(paths.scripts.src.admin + '/**/*.js', adminScripts);
    gulp.watch(paths.scripts.src.public + '/**/*.js', publicScripts);
    gulp.watch(paths.scripts.src.app + '/**/*.js', appScripts);
    gulp.watch(paths.scripts.src.vendor + '/**/*.js', vendorScripts);

    // Styles
    gulp.watch(paths.styles.src, styles);
    gulp.watch(paths.styles.src + '/**/*.scss', adminStyles);
    gulp.watch(paths.styles.vendor.src + '/**/*', copyVendorFiles);

    // Theme PHP files
    gulp.watch(paths.theme + '/**/*.php').on('change', browserSync.reload);
}

function browserSyncServe(done) {
    browserSync.init({
        proxy: 'https://erawanpgnd.myplan.hu',
        open: false,
        notify: {
            styles: [
                'display: none',
                'padding: 15px',
                'font-family: sans-serif',
                'position: fixed',
                'font-size: 0.9em',
                'z-index: 9999',
                'bottom: 0px',
                'left: 0px',
                'border-top-right-radius: 5px',
                'background-color: #1B2032',
                'margin: 0',
                'color: white',
                'text-align: center',
            ],
        },
    });
    done();
}

function deploy() {
    const conn = ftp.create({
        host: process.env.SFTP_HOST,
        user: process.env.SFTP_USER,
        password: process.env.SFTP_PASSWORD,
        parallel: 3,
        log: console.log,
        idleTimeout: 10000,
        secure: true,
        port: process.env.SFTP_PORT,
    });

    const globs = [paths.theme + '/**/*'];

    return gulp
        .src(globs, { base: themeDir, buffer: false })
        .pipe(logFileOperation('deploy'))
        .pipe(conn.newer(process.env.SFTP_DESTINATION))
        .pipe(conn.dest(process.env.SFTP_DESTINATION))
        .on('error', (err) => {
            console.error('Error:', err);
            process.exit(1);
        })
        .on('data', (file) => {
            console.log(`Uploaded: ${file.path}`);
        })
        .on('end', () => console.log('Deployment completed successfully.'));
}

const js = gulp.series(adminScripts, publicScripts, vendorScripts);
const build = gulp.series(js, styles, copyVendorFiles);
const serve = gulp.parallel(watchFiles, browserSyncServe);
function watch() {
    // Scripts
    gulp.watch(paths.scripts.src.admin + '/**/*.js', adminScripts);
    gulp.watch(paths.scripts.src.public + '/**/*.js', publicScripts);
    gulp.watch(paths.scripts.src.app + '/**/*.js', appScripts);
    gulp.watch(paths.scripts.src.vendor + '/**/*.js', vendorScripts);

    // Styles
    gulp.watch(paths.styles.src + '/**/*.scss', styles);
    gulp.watch(paths.styles.src + '/**/*.scss', adminStyles);
    gulp.watch(paths.styles.vendor.src + '/**/*', copyVendorFiles);
}

exports.adminScripts = adminScripts;
exports.publicScripts = publicScripts;
exports.vendorScripts = vendorScripts;
exports.styles = styles;
exports.adminStyles = adminStyles;
exports.copyVendorFiles = copyVendorFiles;
exports.js = js;
exports.build = build;
exports.serve = serve;
exports.watch = watch;
exports.deploy = deploy;
exports.default = build;
