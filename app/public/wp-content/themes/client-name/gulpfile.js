import gulp from 'gulp';
import sassGlob from 'gulp-sass-glob';
import jsonToScss from '@valtech-commerce/json-to-scss';
import {readFile, writeFile} from 'fs';
import sass from 'gulp-dart-sass';
import sourcemaps from 'gulp-sourcemaps';

// Generate SCSS variables from theme-vars.json file
gulp.task('scss-variables', (done) => {
	readFile(`./theme-vars.json`, 'utf8', async (error, theme) => {
		if (error) {
			console.log(error);
			done();
		}
		const scss = jsonToScss.convert(`${theme}`);
		if (scss) {
			await writeFile('scss/_variables.scss', scss, '', () => {
				console.log('theme.json converted to SCSS variables');
				done();
			});
		} else {
			console.log('Problem with converting theme.json to SCSS variables');
			done();
		}
	});

});

// WordPress theme.json generator
gulp.task('theme-json', async (done) => {
	readFile(`./theme-vars.json`, 'utf8', async (error, data) => {
		if (error) {
			console.log(error);
			done();
		}

		const theme = JSON.parse(data);
		const wpFormat = {
			colorPalette: Object.entries(theme.colours).map(([name, value]) => {
				return {
					name: name,
					slug: name,
					color: value,
				};
			}),
			gradientPalette: Object.entries(theme.colours)
				.filter(([name, value]) => ['primary', 'secondary'].includes(name))
				.map(([name, value]) => {
					return [
						{
							name: `${name} + light`,
							slug: `${name}-light`,
							gradient: `linear-gradient(180deg, ${value} 50%, ${theme.colours.light} 50%)`,
						},
						{
							name: `Light + ${name}`,
							slug: `light-${name}`,
							gradient: `linear-gradient(180deg, ${theme.colours.light} 50%, ${value} 50%)`,
						},
						{
							name: `${name} + dark`,
							slug: `${name}-dark`,
							gradient: `linear-gradient(180deg, ${value} 50%, ${theme.colours.dark} 50%)`,
						},
						{
							name: `Dark + ${name}`,
							slug: `dark-${name}`,
							gradient: `linear-gradient(180deg, ${theme.colours.dark} 50%, ${value} 50%)`,
						},
						{
							name: `${name} + white`,
							slug: `${name}-white`,
							gradient: `linear-gradient(180deg, ${value} 50%, ${theme.colours.white} 50%)`,
						},
						{
							name: `White + ${name}`,
							slug: `white-${name}`,
							gradient: `linear-gradient(180deg, ${theme.colours.white} 50%, ${value} 50%)`,
						},
					];
				}).flat().concat([
					{
						name: 'Light + dark',
						slug: 'light-dark',
						gradient: `linear-gradient(180deg, ${theme.colours.light} 50%, ${theme.colours.dark} 50%)`,
					},
					{
						name: 'Dark + light',
						slug: 'dark-light',
						gradient: `linear-gradient(180deg, ${theme.colours.dark} 50%, ${theme.colours.light} 50%)`,
					},
					{
						name: 'Light + white',
						slug: 'light-white',
						gradient: `linear-gradient(180deg, ${theme.colours.light} 50%, ${theme.colours.white} 50%)`,
					},
					{
						name: 'White + light',
						slug: 'white-light',
						gradient: `linear-gradient(180deg, ${theme.colours.white} 50%, ${theme.colours.light} 50%)`,
					},
				]),
		};

		const themeJson = {
			version: 2,
			'$schema': 'https://schemas.wp.org/trunk/theme.json',
			settings: {
				// Defaults
				appearanceTools: false,
				typography: {
					customFontSize: false,
					lineHeight: false,
					dropCap: false,
					fontStyle: false,
					fontWeight: false,
					letterSpacing: false,
					textDecoration: false,
					textTransform: false,
					fontSizes: [],
					fontFamilies: [],
				},
				color: {
					text: false,
					background: true,
					link: false,
					defaultPalette: false,
					defaultGradient: false,
					customGradient: false,
					palette: wpFormat.colorPalette.filter((color) => ['primary', 'secondary', 'accent', 'dark', 'light', 'white'].includes(color.name)),
					gradients: wpFormat.gradientPalette,
				},
				border: {
					radius: false,
					style: false,
					width: false,
					color: false,
				},
			},
		};

		await writeFile('theme.json', JSON.stringify(themeJson, null, 4), '', () => {
			console.log('theme.json created');
			done();
		});
	});
});

// Generate the core WordPress theme stylesheet
gulp.task('theme-css', (done) => {
	gulp.src('scss/style.scss')
		.pipe(sourcemaps.init())
		.pipe(sassGlob())
		.pipe(sass())
		.pipe(sourcemaps.write())
		.pipe(gulp.dest('./'));
	done();
});

// Subset of core shared styles to also be loaded in the editors
gulp.task('editor-css', (done) => {
	gulp.src('scss/styles-tinymce.scss')
		.pipe(sourcemaps.init())
		.pipe(sassGlob())
		.pipe(sass())
		.pipe(sourcemaps.write())
		.pipe(gulp.dest('./'));

	gulp.src('scss/styles-block-editor.scss')
		.pipe(sourcemaps.init())
		.pipe(sassGlob())
		.pipe(sass())
		.pipe(sourcemaps.write())
		.pipe(gulp.dest('./'));

	done();
});

// Style customisations for the WP admin more broadly
gulp.task('admin-css', (done) => {
	gulp.src('scss/styles-admin.scss')
		.pipe(sourcemaps.init())
		.pipe(sassGlob())
		.pipe(sass())
		.pipe(sourcemaps.write())
		.pipe(gulp.dest('./'));
	done();
});

gulp.task('default', function () {
	//gulp.watch('theme-vars.json', { ignoreInitial: false }, gulp.series('scss-variables'));
	gulp.watch(
		['scss/**/*.scss', 'template-parts/**/*.scss', 'components/layout/**/*.scss', 'components/common/*.scss', 'components/blocks/**/*.scss'], {
			ignoreInitial: false,
			events: ['change'],
		},
		gulp.series('theme-css', 'editor-css'),
	);
	gulp.watch('scss/styles-admin.scss', {ignoreInitial: false}, gulp.series('admin-css'));
});
