#POW! Comic Book Manager

A Bootstrap PHP/MySQL web application for maintaining a comic book collection database.

## Requirements
* PHP Web Server
* mySQL

## Installation
* Clone repository to web directory
* Import .sql file to comicdb database
* Change $allowRegistration to "yes" in admin/register.php in order to create the initial user
* edit database connection info in config/db.php

## Development

The site is written in PHP, with Sass (SCSS) for styling and importing [Bootstrap 3](http://getbootstrap.com/) for grid/responsive development.

### Global Development Requirements
* Local PHP Server (LAMP/MAMP)
* [Node.js](//nodejs.org/en/) v5 and up
* [Grunt-js](//gruntjs.com/)
* [Ruby](//www.ruby-lang.org/en/)
* [Sass (Ruby)](//sass-lang.com/)

### Local Development Setup
* Navigate to site directory
* Edit `gruntfile.js` and verify that the PHP server/proxy path from LAMP/MAMP is correct.
* In terminal, run `npm install`. This will install `grunt-contrib-sass` (Sass compiler), `grunt-autoprefixer` (CSS Autoprefixer), `grunt-browser-sync` (Browser Sync), `grunt-contrib-watch` (Watch tasks), `grunt-notify` (Notifier), and `node-sass` (Node Ruby Sass support).
* To run development mode (with watch tasks and auto browser refreshing) run `grunt`. Upon saving any changes in PHP or JS, the browser will refresh the page automatically. For SCSS/CSS updated stylesheets will be injected without a refresh.
* To just recompile all SCSS files, run `grunt build`

## Credits
[Bootstrap for Sass](https://github.com/twbs/bootstrap-sass)

Rich Text Editor by [tinyMCE](//www.tinymce.com)
