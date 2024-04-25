# client-plugin

Boilerplate for building a WordPress plugin in an OOP fashion, intended for site-specific functionality.

If it has a client-specific name by the time you read this, it's not the boilerplate anymore ;)  

## What's included

- Admin notices for required/recommended plugins
- Conditionally loading and saving certain ACF field groups within the plugin, rather than the active theme

## What to add (as needed)
- Custom post types
- Custom taxonomies 
- Custom user roles
- Any other custom functionality related to content and data that should survive any theme change (in the back-end sense) with little to no intervention.

## How to use

1. Update and rename `clientname.php` with your own plugin name, description, author, and text domain.
2. Rename the plugin folder and find & replace `client-plugin` with it throughout.
3. Rename `class-clientname.php` so `clientname` is the all-lowercase name of your plugin
4. Rename and find & replace references to `CLIENTNAME_VERSION` and `CLIENTNAME_PLUGIN_PATH`.
5. Do a case-sensitive find and replace throughout the folder for `clientname`, replacing it with the all-lowercase name of your plugin.
6. Do a case-sensitive find and replace throughout the folder for `ClientName`, replacing it with the ClientName name of your plugin.
7. Remove/modify/add to the provided classes to suit your needs.
8. Add your own classes for the units of functionality you require.
9. More code stuff. Build all the things.
10. Profit.

## General intentions and advice

I use this with my own theme starterkits ([classic](https://github.com/doubleedesign/doublee-theme-starter-kit-classic) or [blocks](https://github.com/doubleedesign/doublee-foundation-theme)) and [base plugin](https://github.com/doubleedesign/doublee-base-plugin) to create custom sites with clear separation of concerns as much as is practical. As a guide:
- Code related to front-end design and content display belongs in the theme
- Custom functionality, custom post types, custom taxonomies, modifications to WordPress functionality (including the admin UI), site-specific data structures and management belong in the plugin.
