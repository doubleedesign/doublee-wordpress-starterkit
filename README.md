# Double-E Design WordPress site blueprint

## Included as submodules
- Parent theme
- Customised block editor plugin (minor alterations and additions, updated with official plugin periodically)
- Base plugin of my common customisations
- Breadcrumbs plugin

## Included starterkit for individual sites
- Client theme boilerplate
- **Still to come:** Client-specific plugin boilerplate

## Initial setup

### Installing into fresh Local site
1. Initialise it as a Git repo `git init`
2. Copy the `.gitignore` file from this repo 
3. Do an initial commit (`git commit -m "Initial commit"`)
4. `git remote add origin https://github.com/doubleedesign/doublee-wordpress-starterkit` to allow pulling this repo into the non-empty install directory
5. `git pull origin master --allow-unrelated-histories` to pull in this main repo
6. `git submodule update --init` to pull in the submodules

### Site-level steps I hope to automate (but for now, are manual)
- Case-sensitive find-and-replace in `themes/client-name`
- Rename `client-name` theme folder
- Get latest versions of [vue.esm-browser](https://unpkg.com/browse/vue@3.4.23/dist/) (from then select the latest version from that link) and [Vue SFC loader](https://cdn.jsdelivr.net/npm/vue3-sfc-loader/dist/vue3-sfc-loader.js) for client theme and update the latter's version in `inc/frontend/class-frontend.php`
- Install dependencies (`npm install`)
- Update `theme-vars.json` in client theme
- Run Gulp scripts to generate `theme.json`, CSS files etc

### Site-level manual steps
- Install ACF Pro and enter licence key
- Install Advanced Editor Tools (TinyMCE Advanced)
- As needed, install Ninja Forms + extensions, WooCommerce + extensions, etc
- Import TinyMCE settings (from `setup/tinymce-settings.json`)
- Update Font Awesome URL in client theme (`inc/frontend/class-frontend.php`)
- Add screenshot.png to client theme folder
- Update any cloud-hosted font paths in all files in `scss` folder in client theme
- Activate client theme

## Development notes

### When updating theme-vars.json
- Run `gulp theme-json` again
- Run `gulp scss-variables` again before running `gulp theme-css`

### Troubleshooting
- Note that file paths in `.gitmodules`, `.gitignore`, and any future automated scripts use Flywheel's directory structure where the WordPress install is in `app/public/` so may need to be updated for other setups
- Check Node version against range in `package.json` (due to Gulp compatibility)
- Make sure `_variables.scss` is generated before trying to generate any other styles
- If WP is loading old colours, make sure you have updated `theme.json` (using `gulp theme-json` unless that would overwrite changes in which case update the file manually)
