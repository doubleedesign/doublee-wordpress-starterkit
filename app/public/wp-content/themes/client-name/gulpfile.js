import gulp from 'gulp';
import sassGlob from 'gulp-sass-glob';
import sass from 'gulp-dart-sass';
import sourcemaps from 'gulp-sourcemaps';

import { variables, themeJson, theme, components, blocks, editor, admin } from '../starter-kit-blocks/gulpfile.js';

function block_styles_doublee_overrides() {
	return gulp.src('blocks/doublee/doublee.scss', { base: 'blocks' })
		.pipe(sourcemaps.init())
		.pipe(sassGlob())
		.pipe(sass().on('error', sass.logError))
		.pipe(sourcemaps.write())
		.pipe(gulp.dest('blocks'));
}

function watchFiles() {
	const options = { events: ['change', 'add', 'unlink'], ignoreInitial: false};

	// Recompile everything if the theme variables change
	gulp.watch('theme-vars.json', options, gulp.series(variables, themeJson, components, blocks, theme, editor, admin));

	// Compile the whole-theme stylesheet and editor styles when anything other than _variables.scss changes
	gulp.watch(['common/scss/**/*.scss', 'components/**/*.scss', 'blocks/**/*/scss', '!**/_variables.scss'], options, gulp.parallel(theme, editor));

	// General UI components
	gulp.watch('components/**/*.scss', options, components);

	// Block SCSS
	gulp.watch('blocks/**/*.scss', options, gulp.parallel(blocks, block_styles_doublee_overrides));
}

export default watchFiles;
