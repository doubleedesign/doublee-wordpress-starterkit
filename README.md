# Double-E Design WordPress site blueprint

- [Prerequisites](#prerequisites)
- [Common submodules](#common-submodules)
- [Starterkits for individual sites](#starterkits-for-individual-sites)
- [Initial setup](#initial-setup)
  - [Installing into fresh WordPress install](#installing-into-fresh-wordpress-install)
  - [Automated setup script](#automated-setup-script)
  - [General manual setup steps](#general-manual-setup-steps)
  - [Theme setup steps](#theme-setup-steps)
- [Miscellaneous development notes](#miscellaneous-development-notes)
- [Warranty (lack thereof)](#warranty)
- [Contributing](#contributing)

---
## Prerequisites
- Git 
- [Node.js](https://nodejs.org/en)
- [Gulp](https://gulpjs.com/) installed globally
- Local development environment set up with WordPress installed and running
- Intermediate to advanced knowledge of WordPress, PHP, HTML, SCSS, JS, etc as it pertains to developing custom themes and plugins according to the requirements that brought you here. While a fair bit of boilerplate layout and design stuff is included for common requirements, if you don't want to write code this isn't the starterkit for you. 

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
```
```
git commit -m "Initial commit"
```
#### For a new project

5. Allow pulling this repo into the non-empty install directory by adding it as the upstream:
```
git remote add upstream https://github.com/doubleedesign/doublee-wordpress-starterkit
```
6. Pull in this main repo, using either `classic` or `blocks` branch<sup>1</sup>
```
git pull upstream classic --allow-unrelated-histories --ff --strategy-option=theirs
```
```
git pull upstream blocks --allow-unrelated-histories --ff --strategy-option=theirs
```
7. Delete the default theme folders if you haven't already
```
rm -rf app/public/wp-content/themes/twentytwenty*
```
8. Pull in the submodules for your chosen branch with:
```
git submodule update --init
```
9. If the block branch pulls the `main` branch of `doublee-gutenberg` instead of `release`, (you can tell, it'll have _everything_ instead of just a couple of folders and PHP files) go to that directory and check out the `release` branch.
```
cd app/public/wp-content/plugins/doublee-gutenberg
```
```
git checkout release
```
##### Footnotes
1. `--strategy-option=theirs` (equivalent to `-X theirs`) automatically resolves any merge conflicts preferring the files in this repo; if you're working with a fresh install, that would only be the temporary `.gitignore` from step 2, as intended. Skip this if you expect conflicts you want to resolve manually.

#### For working on the starterkit itself

5. Allow pulling this repo into the non-empty install directory by adding it as the origin:
```
git remote add origin https://github.com/doubleedesign/doublee-wordpress-starterkit
```
6. Fetch the latest:
```
git fetch origin
```
7. Reset local `master` to match `origin/master`:
```
git reset --hard origin/master
```
8. Delete the default theme folders  if you haven't already
```
rm -rf app/public/wp-content/themes/twentytwenty*
```
9. If you're going to work on a non-master branch, check it out
```
git checkout classic
git checkout blocks
```
10. Pull in the submodules for your chosen branch with:
```
git submodule update --init
```
11. If the block branch pulls the `main` branch of `doublee-gutenberg` instead of `release`, (you can tell, it'll have _everything_ instead of just a couple of folders and PHP files) go to that directory and check out the `release` branch.
```
cd app/public/wp-content/plugins/doublee-gutenberg
```
```
git checkout release
```

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
```
```
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

##### Footnotes
1. Because it's a boilerplate for a custom theme, not a parent theme that could be updated without manual intervention.

#### Block-based theme
After running the setup script as described above:

1. Check the theme's README for any additional or updated setup steps that I may have missed here
2. Get latest versions of [vue.esm-browser](https://unpkg.com/browse/vue@3.4.23/dist/) (from then select the latest version from that link) and [Vue SFC loader](https://cdn.jsdelivr.net/npm/vue3-sfc-loader/dist/vue3-sfc-loader.js) for client theme and update the latter's version in `inc/frontend/class-frontend.php`
3. Update [Font Awesome](https://fontawesome.com/kits) URL
4. Install dependencies (`npm install`)
5. Update `theme-vars.json` in client theme 
6. Run Gulp scripts to generate `theme.json`, CSS files etc 
7. Add screenshot.png
8. Update any cloud-hosted font paths in all files in `scss` folder
9. Check that the theme path at the top of `wp-content/themes/YOUR_THEME/js/vue-components.js` is correct 
10. Activate theme.

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

---
## Warranty

There isn't one.

I have developed this starterkit for my own use, and written this documentation primarily for my future self. Sometimes I build a bunch of WordPress sites close together, and other times I might go 6 months between new builds, so I put all of this together for my own efficiency and consistency. In the spirit of open source, have posted it here for the benefit of other developers to pay it forward in tribute to how I benefitted from the likes of [Brandon Jones](https://www.youtube.com/makedesignnotwar)'s Super Skeleton and [Ole Fredrik Lie](https://github.com/olefredrik)'s [FoundationPress](https://github.com/olefredrik/FoundationPress) earlier in my career, and continue to benefit from other open source developers' work. 

WordPress is no longer my full-time career and so I unfortunately cannot consistently dedicate time to this open source work outside of what directly benefits me and my clients. Suggestions, feature requests, bug reports, and general feedback are very welcome (I'd love to hear if you find this useful!) and I will endeavour to respond when I have time cand capacity, but ultimately use of this kit (including the referenced submodules) is at your own risk and the results are your own responsibility. 

---
## Contributing
Now to pivot from the "can't help ya" negativity of the warranty section above, I would like to emphasise that feedback is absolutely welcome (I just can't promise if or when I'll action it). If you do find this kit and/or its associated submodules that I developed or forked and modified useful and add features, fix bugs, improve the existing functionality, etc., I would appreciate it if, in the spirit of open source, you would contribute it back via pull request in the relevant repository (i.e., either here or in the submodule's repo).

If you are not able to make the change yourself, please feel free to raise the suggestion / report the bug by creating an issue in the relevant repository.

Thank you for reading, thanks in advance for any contributions or feedback you may have, and happy coding! 
