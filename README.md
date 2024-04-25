# Double-E Design WordPress site blueprint

- [Common submodules](#common-submodules)
- [Starterkits for individual sites](#starterkits-for-individual-sites)
- [Initial setup](#initial-setup)
  - [Installing into fresh WordPress install](#installing-into-fresh-wordpress-install)
  - [Automated setup script](#automated-setup-script)
  - [General manual setup steps](#general-manual-setup-steps)
  - [Theme setup steps](#theme-setup-steps)
- [Miscellaneous development notes](#miscellaneous-development-notes)

---
## Common submodules
Repositories common to many sites (including those not developed by me, if available) are included as submodules for easier updating. 

### Common to both branches:
- [Base plugin of my common customisations](https://github.com/doubleedesign/doublee-base-plugin)
- [Breadcrumbs plugin](https://github.com/doubleedesign/doublee-breadcrumbs)

### Classic branch:
- [Classic Editor](https://github.com/WordPress/classic-editor)

### Block Editor branch:
- [Parent theme (for block-based sites)](https://github.com/doubleedesign/doublee-foundation-theme)
- [Customised block editor plugin](https://github.com/doubleedesign/doublee-gutenberg) (minor alterations and additions, updated with official plugin periodically); be sure to track the `release` branch of this repo to copy down just what's required for production

---
## Starterkits for individual sites
Included in this repo:
- Client theme boilerplate for block-based sites (instructions below for classic site theme starterkit)
- Client plugin boilerplate

---
## Initial setup

### Installing into fresh WordPress install
1. Initialise it as a Git repo
```
git init
```
3. Create a basic `.gitignore` file to avoid committing pretty much anything initially (this is temporary and will be overwritten by this repo's `.gitignore`)
```
conf
logs
setup
.idea
debug.log
local-xdebuginfo.php
app/public/
```
4. Do an initial commit 
```
git add .gitignore
git commit -m "Initial commit"
```
6. Allow pulling this repo into the non-empty install directory by adding it as the upstream
```
git remote add upstream https://github.com/doubleedesign/doublee-wordpress-starterkit
```
5. Pull in this main repo, using either `classic` or `blocks` branch<sup>2</sup>
```
git pull upstream classic --allow-unrelated-histories --ff --strategy-option=theirs
```
```
git pull upstream blocks --allow-unrelated-histories --ff --strategy-option=theirs
```

6. Pull in the submodules for your chosen branch with:
```
git submodule update --init
```

#### Footnotes
1. Because it's a boilerplate for a custom theme, not a parent theme that could be updated without manual intervention.
2. `--strategy-option=theirs` (equivalent to `-X theirs`) automatically resolves any merge conflicts preferring the files in this repo; if you're working with a fresh install, that would only be the temporary `.gitignore` from step 2, as intended. Skip this if you expect conflicts you want to resolve manually.

---
### Automated setup script 
Run the setup script and follow the prompts to do a find-and-replace across the client theme (for block-based sites) and client plugin boilerplates and rename the relevant files and folders with the client/project name.

**Note:** The file paths are set up for Local by Flywheel. Update them accordingly if you have a different setup.

```
node setup/setup.js
```
---
### General manual setup steps 
- Install [ACF Pro](https://www.advancedcustomfields.com/pro/) and enter licence key
- Install [Advanced Editor Tools (TinyMCE Advanced)](https://en-au.wordpress.org/plugins/tinymce-advanced/)
- As needed, install Ninja Forms + extensions, WooCommerce + extensions, etc
- Import TinyMCE settings from `setup/tinymce-settings.json`.
  
---
### Theme setup steps

#### Classic theme
After running the setup script as described above:

1. Clone the theme 
```
cd app/public/wp-content/themes
git clone https://github.com/doubleedesign/doublee-theme-starter-kit-classic
```
2. Unlink it from that repo<sup>1</sup>
```
git remote rm origin
```

3. Update Font Awesome URL 
4. Add screenshot.png
5. Update any cloud-hosted font paths in all files in `scss` folder.
6. Check and perform any other setup setups in the theme's README.

#### Block-based theme
After running the setup script as described above:

1. Check the theme's README for any additional or updated setup steps that I may have missed here
2. Get latest versions of [vue.esm-browser](https://unpkg.com/browse/vue@3.4.23/dist/) (from then select the latest version from that link) and [Vue SFC loader](https://cdn.jsdelivr.net/npm/vue3-sfc-loader/dist/vue3-sfc-loader.js) for client theme and update the latter's version in `inc/frontend/class-frontend.php`
3. Install dependencies (`npm install`)
4. Update `theme-vars.json` in client theme 
5. Run Gulp scripts to generate `theme.json`, CSS files etc 
6. Update Font Awesome URL
7. Add screenshot.png
8. Update any cloud-hosted font paths in all files in `scss` folder
9. Activate theme.

---
## Miscellaneous development notes

### When updating theme-vars.json
- Run `gulp theme-json` again
- Run `gulp scss-variables` again before running `gulp theme-css`

### Troubleshooting
- Note that file paths in `.gitmodules`, `.gitignore`, and any future automated scripts use Flywheel's directory structure where the WordPress install is in `app/public/` so may need to be updated for other setups
- Check Node version against range in `package.json` (due to Gulp compatibility)
- Make sure `_variables.scss` is generated before trying to generate any other styles
- If WP is loading old colours, make sure you have updated `theme.json` (using `gulp theme-json` unless that would overwrite changes in which case update the file manually)
