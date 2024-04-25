# Double-E Design WordPress site blueprint

## Included as submodules
- Parent theme (for block-based sites)
- Customised block editor plugin (minor alterations and additions, updated with official plugin periodically)
- Base plugin of my common customisations
- Breadcrumbs plugin

## Included starterkit for individual sites
- Client theme boilerplate (for block-based sites; integrating [Classic theme](https://github.com/doubleedesign/doublee-theme-starter-kit-classic) option is in the roadmap)
- Client plugin boilerplate

## Initial setup

### Installing into fresh Local site
1. Initialise it as a Git repo `git init`
2. Copy the `.gitignore` file from this repo 
3. Do an initial commit (`git commit -m "Initial commit"`)
4. `git remote add origin https://github.com/doubleedesign/doublee-wordpress-starterkit` to allow pulling this repo into the non-empty install directory
5. `git pull origin master --allow-unrelated-histories` to pull in this main repo
6. `git submodule update --init` to pull in the submodules

### Site-level steps I hope to automate (but for now, are manual)

#### Theme:
1. Case-sensitive find-and-replace in `themes/client-name`
2. Rename `client-name` theme folder
3. Get latest versions of [vue.esm-browser](https://unpkg.com/browse/vue@3.4.23/dist/) (from then select the latest version from that link) and [Vue SFC loader](https://cdn.jsdelivr.net/npm/vue3-sfc-loader/dist/vue3-sfc-loader.js) for client theme and update the latter's version in `inc/frontend/class-frontend.php`
4. Install dependencies (`npm install`)
5. Update `theme-vars.json` in client theme 
6. Run Gulp scripts to generate `theme.json`, CSS files etc

#### Plugin:
1. Update and rename `clientname.php` with your own plugin name, description, author, and text domain.
2. Rename the plugin folder and find & replace `doublee-client-plugin` with it throughout.
3. Rename `class-clientname.php` so `clientname` is the all-lowercase name of your plugin
4. Rename and find & replace references to `CLIENTNAME_VERSION` and `CLIENTNAME_PLUGIN_PATH`.
5. Do a case-sensitive find and replace throughout the folder for `clientname`, replacing it with the all-lowercase name of your plugin.
6. Do a case-sensitive find and replace throughout the folder for `ClientName`, replacing it with the ClientName name of your plugin.

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
