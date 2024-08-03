# Storybook for WordPress theme development

This package uses a basic local PHP server and [Storybook](https://storybook.js.org/) with the [Storybook for Server addon](https://www.npmjs.com/package/@storybook/server-webpack5) to provide a standalone environment to develop, test, and demo WordPress theme UI template partials including, but not limited to, blocks.

## Table of contents
- [Prerequisites](#prerequisites)
  - [Local PHP installation](#local-php-installation)
    - [Existing local web server](#existing-local-web-server)
    - [Windows](#windows)
    - [WSL](#wsl)
    - [MacOS](#macos)
  - [Symlinked relevant theme directories](#symlinked-relevant-theme-directories)
    - [Optional: Persist symlinks across Windows machines through Git](#optional-persist-symlinks-across-windows-machines-through-git)
- [Running Storybook](#running-storybook)
- [Creating stories](#creating-stories)
- [Template output functions](#template-output-functions)
  - [Mocked functions](#mocked-functions)
  - [Real functions](#real-functions)
- [Miscellaneous notes](#miscellaneous-notes)
    
---

## Prerequisites

### Local PHP installation

#### Existing local web server

If you are using a local web server like [Local](https://localwp.com/), [WAMP](https://wampserver.aviatechno.net/), XAMPP, MAMP, etc., I believe you can alias the `php` terminal command to use the PHP installation that comes with that instead of installing it separately if you prefer. In Windows that'd be adding it to your PATH.

#### Windows

You can install PHP in Windows by downloading a zip from [php.net](https://www.php.net/downloads) and extracting it where you want it to live, or running a PowerShell command to install using Chocolatey (the Windows package manager). 

All of my below examples assume it's in `C:/php` so here's how to do that in PowerShell:

```PowerShell
choco install php --params "'/InstallDir:C:\php'"
```
To update:
```PowerShell
choco upgrade php
```
IIRC you then  need to [add it to your PATH](https://www.php.net/manual/en/faq.installation.php#faq.installation.addtopath) to get the `php` terminal alias.

Confirm it works and see the version:
```PowerShell
php -v
```

### WSL

I use WSL for most of my day-to-day CLI needs. You can install PHP within the Linux environment, but I opt to use the Windows PHP installation - which you can access from WSL by symlinking it.

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

### Symlinked relevant theme directories

I am writing this in the context of working with my starterkit parent theme and a child theme for individual projects. Both can contain PHP templates for WordPress blocks, and the child theme contains other elements such as the site header and footer templates.

So that Storybook can access these files in a useful and intuitive structure without duplicating files, I use symlinking.  

| Real directory                                                 | Symlinked directory                      |  
|----------------------------------------------------------------|------------------------------------------|
| `app/public/wp-content/themes/doublee-foundation-theme/blocks` | `storybook/components/blocks/foundation` |
| `app/public/wp-content/themes/client-name/components/blocks`   | `storybook/components/blocks/project`    |
| `app/public/wp-content/themes/client-name/components/layout`   | `storybook/components/layout/project`    |

**Note:** to symlink directories in Windows, you need to do it natively - not in WSL. The above links can be created in PowerShell with the following commands:

```PowerShell
New-Item -ItemType SymbolicLink -Path "components\blocks\foundation" -Target "..\app\public\wp-content\themes\doublee-foundation-theme\blocks"
```
```PowerShell
New-Item -ItemType SymbolicLink -Path "components\blocks\project" -Target "..\app\public\wp-content\themes\client-name\components\blocks"
```
```PowerShell
New-Item -ItemType SymbolicLink -Path "components\layout\project" -Target "..\app\public\wp-content\themes\client-name\components\layout"
```

**Warning:** Using PowerShell within PHPStorm might not have sufficient permissions to create symlinks. To avoid having to open PowerShell as admin and then `cd` around to get to the right directory, here's a handy WSL command to launch an admin PowerShell instance in the current directory: 
```bash
current_dir=$(wslpath -w $(pwd))
powershell.exe -Command "Start-Process powershell -ArgumentList '-NoProfile -ExecutionPolicy Bypass -NoExit -Command Set-Location -LiteralPath \"${current_dir}\"' -Verb RunAs"
```

Now when you run the Storybook server, it will be able to access the relevant theme template files with nice and simple file paths, no worrying about how many `../../`s you need to get a relative path to a real file right. **Remember:** The web server root is the `components` folder, so omit that from file URLs. An example of a valid URL is `http://127.0.0.1:5100/blocks/foundation/core/button/index.php`.

### Optional: Persist symlinks across Windows machines through Git
Enable symlinks in Git:
```bash
git config --global core.symlinks true
```
---
## Running Storybook

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
