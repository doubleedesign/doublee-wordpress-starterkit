# Double-E Design WordPress site blueprint

- [Prerequisites](#prerequisites)
- [Common submodules](#common-submodules)
- [Other required plugins](#other-required-plugins)
- [Starterkits for individual sites](#starterkits-for-individual-sites)
- [Initial setup](#initial-setup)
  - [Installing into fresh WordPress install](#installing-into-fresh-wordpress-install)
  - [Automated setup script](#automated-setup-script)
  - [General manual setup steps](#general-manual-setup-steps)
- [Development](#development)
    - [Multisite setup](#multisite-setup)
    - [Storybook](#storybook)
    - [Updating the branches](#updating-the-branches)
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
Repositories common to many sites (including those not developed by me, if available via GitHub) are included as submodules for easier updating. 

### Common to both branches:
- [Base plugin of my common customisations](https://github.com/doubleedesign/doublee-base-plugin)
- [Breadcrumbs plugin](https://github.com/doubleedesign/doublee-breadcrumbs)

### Classic branch:
- [Classic theme starter kit / parent theme](https://github.com/doubleedesign/doublee-wp-theme-starter-kit-classic)
- [Classic Editor](https://github.com/WordPress/classic-editor)
- [ACF Extended](https://github.com/acf-extended/ACF-Extended)

### Block Editor branch:
- [Block theme starter kit / parent theme](https://github.com/doubleedesign/doublee-wp-theme-starter-kit-blocks)

## Other required plugins
- [Advanced Custom Fields Pro](https://www.advancedcustomfields.com/pro/) 
- [Advanced Editor Tools](https://wordpress.org/plugins/tinymce-advanced/) (also known as TinyMCE Advanced) is included in this repo because at the time of writing, it isn't available on GitHub to use as a submodule.
- The "Theme Starter Kit - Common" plugin included in this repository is required for using either of my starter themes. It contains functionality and customisations that are common to both themes to reduce duplication and unnecessary divergence between them.

---
## Starterkits for individual sites
Included in this repo:
- Client theme boilerplate
- Client plugin boilerplate

---
## Initial setup 

**Note:** If working on the starterkit itself using a fresh installation using Local By Flywheel, ensure you initialise it as multisite during the setup process in Local. 

### Installing into fresh WordPress install
1. Initialise it as a Git repo
```
git init
```
2. Create a basic `.gitignore` file to avoid committing pretty much anything initially (this is temporary and will be overwritten by this repo's `.gitignore`)
```
conf
logs
setup
.idea
debug.log
local-xdebuginfo.php
app/public/
```
3. Do an initial commit 
```
git add .gitignore
```
```
git commit -m "Initial commit"
```

#### For a new project:

4. Allow pulling this repo into the non-empty install directory by adding it as the upstream:
```
git remote add upstream https://github.com/doubleedesign/doublee-wordpress-starterkit
```
5. Pull in this main repo, using either `classic` or `blocks` branch<sup>1</sup>
```
git pull upstream classic --allow-unrelated-histories --ff --strategy-option=theirs
```
```
git pull upstream blocks --allow-unrelated-histories --ff --strategy-option=theirs
```
6. Delete the default theme folders if you haven't already
```
rm -rf app/public/wp-content/themes/twentytwenty*
```
7. Pull in the submodules for your chosen branch with:
```
git submodule update --init
```

#### For working on the starterkit:

See the [Development](#development) section below.

##### Footnotes
1. `--strategy-option=theirs` (equivalent to `-X theirs`) automatically resolves any merge conflicts preferring the files in this repo; if you're working with a fresh install, that would only be the temporary `.gitignore` from step 2, as intended. Skip this if you expect conflicts you want to resolve manually.

---
### Automated setup script 
For new projects, run the setup script and follow the prompts to do a find-and-replace across the client theme and client plugin boilerplates and rename the relevant files and folders with the client/project name.

::warn:: This script has not yet been set up for the classic theme and has not been tested with the block theme since some major updates. `// TODO`

**Note:** The file paths are set up for Local by Flywheel. Update them accordingly if you have a different setup.

```
node setup/setup.js
```

---
### General manual setup steps 

1. Install [ACF Pro](https://www.advancedcustomfields.com/pro/) and enter licence key
2. For classic sites, install [ACF Component Field](https://codecanyon.net/item/advanced-custom-fields-component-field-addon/13770937)
3. Import TinyMCE settings from `setup/tinymce-settings.json` in your selected theme.
4. Add logo, Font Awesome kit ID and external fonts to be loaded in the `<head>` in the global options in the admin (the page will be labelled with your site name)
5. Update any cloud-hosted font paths within the client theme folder
6. Add favicon in Settings > General Settings (the native one, where you set your site name)
7. `cd` into the client theme folder and install dependencies (`npm install`)
8. Update `theme-vars.json` in client theme
9. Run Gulp scripts to generate stylesheets, bundle scripts, etc.
10. Check the parent theme's README.md for any additional setup steps or things to note
11. Activate your child theme 
12. As needed, install Ninja Forms + extensions, WooCommerce + extensions, etc.
13. Add client-specific `screenshot.png` to your child theme folder

---

## Development

To work on the starterkit itself, follow the [Initial Setup][#initial-setup] steps above, then:

1. Allow pulling this repo into the non-empty install directory by adding it as the origin:
```
git remote add origin https://github.com/doubleedesign/doublee-wordpress-starterkit
```
2. Fetch the latest:
```
git fetch origin
```
3. Reset local `develop` to match `origin/develop`:
```
git reset --hard origin/develop
```
4. Delete the default theme folders  if you haven't already
```
rm -rf app/public/wp-content/themes/twentytwenty*
```
5. If appropriate, create and check out a new branch for your work:
```
git checkout -b my-new-feature
```
6. Pull in the submodules:
```
git submodule update --init
```

### Multisite setup

To work on multiple variations of the starterkit, it is ideal to use a multisite installation with one site per theme type. This makes it easier to keep the variations as closely aligned as possible on common features and updates, as well as test things across variations more easily. This approach would also help scale the project if there's a need to add more variations in the future.

1. In `wp-config.php`, add the following line:
```
define('WP_ALLOW_MULTISITE', true);
```
2. Log in to the WordPress admin and go to Tools > Network Setup. Follow the instructions to set up a multisite network.

3. Add sites for each variation (e.g., Classic and Blocks).
4. To ensure that all sites show up in "My sites" in the menu, go into each of the sites' settings > Users tab > ensure the Super Admin account is there.
5. Network activate ACF Pro, Advanced Editor Tools, Breadcrumbs, Theme Starter Kit - Common, Double-E Base Plugin, and Client Name Plugin.
6. Network enable the themes.
7. In Settings, scroll down to Menu settings > Enable Administration Menus and tick "Plugins". 
8. In the Classic site, activate Classic Editor, ACF Component Field, ACF Extended, and the Classic Starterkit theme.
9. In the Blocks site, activate the Block Starterkit theme.

### Storybook

Details to come.

### Updating the branches

 Instructions for bringing changes into the branches still to come. Important thing is not to merge the `.gitmodules` file because master has everything, whereas the variation branches should not.

---
## Miscellaneous development notes

### When updating theme-vars.json
- Run `gulp theme-json` again
- Run `gulp variables` again before running `gulp theme`, `gulp components`, gulp modules`, or `gulp blocks` again

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

