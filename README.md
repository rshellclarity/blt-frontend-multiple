Acquia BLT Frontend Multiple Commands
====

This is an [Acquia BLT](https://github.com/acquia/blt) plugin that builds frontend dependencies and compiles theme assets for multiple themes at once. It works with both Acquia Cloud and Acquia Cloud Site Factory projects.

This plugin is **community-created** and **community-supported**. Acquia does not provide any direct support for this software or provide any warranty as to its stability.

## Quickstart

To use this plugin on your existing BLT 12 project, require the plugin with Composer:

`composer require rshellclarity/blt-frontend-multiple`

Compile multiple themes for development or ACSF deployment by using these custom BLT commands provided by this plugin.


## Installation and Usage

Use Composer to add the following commands to your desired BLT project.

```
composer require rshellclarity/blt-frontend-multiple
blt source:frontend --no-interaction
```
Run `blt source:frontend`  (sfe) to both build and compile all your theme assets in the /themes/custom directory.


You may perform these tasts separately using these commands:

`blt source:frontend:build`  (sfe:build) - Builds the Node and Gulp frontend requirements and their dependencies for each custom theme in your project using NPM.

`blt source:frontend:compile` (sfe:compile) - Compiles the frontend assets for each custom theme in your project using Gulp and Node SASS.

`blt source:frontend:reset` (sfe:reset) - Removes all the requirement and dependency packages from your themes and starts again.


Shell scripts are also included to use with non-BLT tooling:

`bash build-frontend-reqs.sh`

`bash compile-frontend-assets.sh`