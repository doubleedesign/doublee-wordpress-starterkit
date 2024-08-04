# Storybook for WordPress theme development

This package uses a basic local PHP server and [Storybook](https://storybook.js.org/) with the [Storybook for Server addon](https://www.npmjs.com/package/@storybook/server-webpack5) to provide a standalone environment to develop, test, and demo WordPress theme UI template partials including, but not limited to, blocks.

## Table of contents
- [Some technical context](#some-technical-context)
  - [Glossary](#glossary)
- [Prerequisites and setup](#prerequisites-and-setup)
  - (Windows only) [PowerShell permissions](#powershell-permissions)
  - [Local PHP installation](#local-php-installation)
  - [WordPress theme(s)](#wordpress-themes)
  - [Symlinked relevant theme directories](#symlinked-relevant-theme-directories)
  - [Local Node installation](#local-node-installation)
  - [Storybook and its dependencies](#storybook-and-its-dependencies)
- [Running this thing](#running-this-thing)
- [Creating stories](#creating-stories)
- [Template output functions](#template-output-functions)
  - [Mocked functions](#mocked-functions)
  - [Real functions](#real-functions)
- [Miscellaneous notes](#miscellaneous-notes)
    
---

## Some technical context

This documentation is written for the very broad audience of WordPress theme developers. I assume you have experience with HTML, CSS, and PHP in the WordPress theme development context. I have _not_ assumed that you have any particular setup or tools installed on your machine, that you are a seasoned command line user, or that you work with JavaScript applications regularly. If you do, there will be sections you can skip.

I do all of my WordPress development on Windows and use WSL as my terminal of choice, but there are a number of things you need to do natively in Windows to set this project up, and for those I use PowerShell. This documentation reflects these things. I use a Mac for my (JavaScript-based) day job, so general information about MacOS is included where I know it. This all said, once you have set everything up, day-to-day commands for this project are the same everywhere.

### Glossary
| Term       | Definition                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                              |
|------------|---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------|
| WSL        | <p>[Windows Subsystem for Linux](https://learn.microsoft.com/en-us/windows/wsl/faq). This allows you to run a Linux terminal within Windows, which provides a Bash shell as opposed to the Command Prompt or PowerShell. You can install WSL [through PowerShell](https://learn.microsoft.com/en-us/windows/wsl/install) or through the Microsoft Store by finding your Linux distribution of choice (mine is [Debian](https://www.microsoft.com/store/productId/9MSVKQC78PK6?ocid=pdpshare); Ubuntu is also popular).</p><p>For one thing, because a lot of developer tutorials and documentation use Bash (presumably because of the prevalence of MacOS users amongst us + many web servers running on Linux) this can save you a translation step in a lot of situations; plus you can customise it which can be really handy if you're inclined to spend the time. For example, I use [Oh My ZSH](https://ohmyz.sh/) with the Git, [autosuggestions](https://github.com/zsh-users/zsh-autosuggestions/blob/master/INSTALL.md), and [syntax highlighting](https://github.com/zsh-users/zsh-syntax-highlighting/blob/master/INSTALL.md) plugins.</p> |
| NVM        | Node Version Manager. This is a tool that allows you to manage multiple versions of Node.js on your machine. It's useful because different projects can require different versions of Node, and it makes it easy to keep your main Node version up-to-date.                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                             |
| NPM        | Node Package Manager. This is a tool that comes with Node and allows you to install and manage JavaScript packages. For context, it's like Composer for PHP.                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                            |
| Chocolatey | OS-level package manager built in to Windows that allows you to install and manage things like PHP and Node from the command line.                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                      |
| Homebrew   | OS-level package manager for MacOS that allows you to install and manage things like PHP and Node from the command line.                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                |
| APT        | Advanced Package Tool, the OS-level built-in package manager for Debian-based Linux distributions that allows you to install and manage things like PHP and Node.                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                       |


---

## Prerequisites and setup

TL;DR you will need:
- [PHP](#local-php-installation) and [Node](#local-node-installation) installed and available to your terminal of choice,
- [your theme(s)](#wordpress-themes) present where they would usually be, 
- some [symlinks](#symlinked-relevant-theme-directories) set up, 
- and the Storybook dependencies installed with [NPM](#glossary) or equivalent.

### PowerShell permissions

Using PowerShell within a non-admin terminal might not have sufficient permissions to install things and create symlinks. Supposedly [enabling developer features](https://learn.microsoft.com/en-us/windows/apps/get-started/enable-your-device-for-development) in Windows can help with this, but that has not been my experience.

To avoid having to open PowerShell separately with admin privileges and then `cd` around to get to the right directory, here's some handy WSL commands to set a variable for the local directory and then launch an admin PowerShell instance there:
```bash
current_dir=$(wslpath -w $(pwd))
```
```bash
powershell.exe -Command "Start-Process powershell -ArgumentList '-NoProfile -ExecutionPolicy Bypass -NoExit -Command Set-Location -LiteralPath \"${current_dir}\"' -Verb RunAs"
```

### Local PHP installation

#### Existing local web server

If you are using a local web server like [Local](https://localwp.com/), [WAMP](https://wampserver.aviatechno.net/), XAMPP, MAMP, etc., I believe you can alias the `php` terminal command to use the PHP installation that comes with that instead of installing it separately if you prefer. In Windows that'd involve [adding it to your PATH](https://www.php.net/manual/en/faq.installation.php#faq.installation.addtopath).

#### Windows (native)

You can install PHP in Windows by downloading a zip from [php.net](https://www.php.net/downloads) and extracting it where you want it to live, or running a PowerShell command to install using [Chocolatey](#glossary). You will need to [run PowerShell with admin privileges](#powershell-permissions).

All of my below examples assume it's in `C:/php` so here's how to do that in PowerShell:

```PowerShell
choco install php --params "'/InstallDir:C:\php'"
```
To update:
```PowerShell
choco upgrade php
```
You then  need to [add it to your PATH](https://www.php.net/manual/en/faq.installation.php#faq.installation.addtopath) to get the `php` terminal alias.

Confirm it works and see the version:
```PowerShell
php -v
```

#### WSL

I use [WSL](#glossary) for most of my day-to-day CLI needs. You can install PHP within its Linux environment, but I opt to use the Windows PHP installation - which you can access from WSL by symlinking it.

Assuming PHP is installed in C:/php, run the following from a WSL terminal:
```bash
sudo ln -s /mnt/c/php/php.exe /usr/local/bin/php
```
Confirm it works and see the version:
```bash
php -v
```

_Why do I do that, you ask? I tried to install it in WSL using `sudo apt install php` and got errors, and thought "Fuck it, don't I already have PHP on this machine? Can I use that without switching terminals?" and the answer was yes._

#### MacOS
 ¯\_(ツ)_/¯ Use Homebrew, I assume.

### WordPress theme(s)

If you're using your own theme(s), make sure they're where they should be in the WordPress directory tree and skip the rest of this section.

My parent theme is included in this repository as a submodule, i.e., it's its own repository as well, so it needs to be pulled in when setting this project up if you're going to use it. To pull in _just_ the theme and not the plugins, run:

```bash
git submodule update --init app/public/wp-content/themes/doublee-foundation-theme
```

**Note:** If you get permissions errors in WSL, just throw in `sudo` at the start of that command.

### Symlinked relevant theme directories

I am writing this in the context of working with my starterkit parent theme and a child theme for individual projects. Both can contain PHP templates for WordPress blocks, and the child theme contains other elements such as the site header and footer templates.

So that Storybook can access these files in a useful and intuitive structure without duplicating files, I use symlinking. Here's how I set it up for themes I'm developing using my [block theme starterkit](https://github.com/doubleedesign/doublee-wordpress-starterkit/tree/blocks):

| Real directory                                                 | Symlinked directory                      |  
|----------------------------------------------------------------|------------------------------------------|
| `app/public/wp-content/themes/doublee-foundation-theme/blocks` | `storybook/components/blocks/foundation` |
| `app/public/wp-content/themes/client-name/components/blocks`   | `storybook/components/blocks/project`    |
| `app/public/wp-content/themes/client-name/components/layout`   | `storybook/components/layout/project`    |

**Note:** to symlink directories in Windows, you need to do it natively - not in WSL. The above links can be created in PowerShell with the following commands (note: you will need admin privileges):

```PowerShell
New-Item -ItemType SymbolicLink -Path "components\blocks\foundation" -Target "..\app\public\wp-content\themes\doublee-foundation-theme\blocks"
```
```PowerShell
New-Item -ItemType SymbolicLink -Path "components\blocks\project" -Target "..\app\public\wp-content\themes\client-name\components\blocks"
```
```PowerShell
New-Item -ItemType SymbolicLink -Path "components\layout\project" -Target "..\app\public\wp-content\themes\client-name\components\layout"
```

Now when you run the Storybook server, it will be able to access the relevant theme template files with nice and simple file paths, no worrying about how many `../../`s you need to get a relative path to a real file right. **Remember:** The web server root is the `components` folder, so omit that from file URLs. An example of a valid URL is `http://127.0.0.1:5100/blocks/foundation/core/button/index.php`.

To enable persisting symlinks across machines through Git, you can try running the below command, but I have found that this doesn't work as expected for directories in Windows, so I just recreate the symlinks on each machine I work on because I only work on two machines, so it's not a big deal. 

```bash
git config --global core.symlinks true
```

### Local Node installation

Storybook itself is a JavaScript application, so you'll need Node installed to install its dependencies and run it.

#### Windows (native) 
There are multiple options for installing Node.js on Windows:
1. GUI: Installation wizard from [nodejs.org](https://nodejs.org/en/download/), but then you're kind of stuck with one version (not recommended)
2. Terminal: You can [install NVM for Windows using PowerShell/Chocolatey](https://tertiumnon.medium.com/install-nvm-on-windows-fd5008ab5a71) and then use `nvm` commands to manage Node versions
3. Middle ground: Installation wizard for [NVM for Windows](https://github.com/coreybutler/nvm-windows) and then use `nvm` commands in your native terminal of choice to manage Node versions

#### WSL

Install NVM and Node just as you would for a Linux environment. 
- [NVM installation documentation](https://github.com/nvm-sh/nvm?tab=readme-ov-file#installing-and-updating)
- [Microsoft documentation](https://learn.microsoft.com/en-us/windows/dev-environment/javascript/nodejs-on-wsl#install-nvm-nodejs-and-npm) (this references WSL2 with Ubuntu and I use WSL1 with Debian, but it's the same process)

#### MacOS
Install NVM and manage Node versions using [Homebrew](https://formulae.brew.sh/formula/nvm#default).

### Storybook and its dependencies

Once you have Node installed, install Storybook and its dependencies from the `storybook` directory:
```bash
npm install
```

---
## Running this thing

To come.

---

## Creating stories

To come.

---

## Template output functions

// TODO how to get the files to use the mocked functions

### Mocked functions

To enable the development of theme UI components in isolation within a lightweight environment, I have configured the setup so that an active WordPress installation is not required to run Storybook and develop, test, and demo these components. Instead, mocked versions of WordPress, ACF, and other plugin functions are used to provide the necessary demo data to the templates.

### Real functions

While functions from WordPress, ACF, and other plugins are mocked, any function that is part of the themes themselves use the actual implementation.

---

## Miscellaneous notes

To come.
