'use strict';

module.exports = function(grunt) {
  require('load-grunt-tasks')(grunt);

  // Project configuration.
  grunt.initConfig({
    pkg: grunt.file.readJSON('package.json'),
    project: {
      app: ['.'],
      css: ['<%= project.app %>/styles']
    },
    // Sass -> CSS
    sass: {
      dev: {
        options: {
          style: 'expanded'
        },
        files: {
            "<%= project.app %>/styles.css": "<%= project.css %>/styles.scss"
        }
      },
      build: {
        options: {
          style: 'expanded',
          sourcemap: 'none'
        },
        files: {
            "<%= project.app %>/styles.css": "<%= project.css %>/styles.scss"
        }
      }
    },
    // Adds any relevate autoprefixers supporting IE 11 and above
    autoprefixer: {
      options: {
        browsers: ["> 1%", "ie > 10"],
        map: true
      },
      target: {
        files: {
          "<%= project.app %>/styles.css": "<%= project.app %>/styles.css"
        }
      }
    },
    notify: {
      sass:{
        options:{
          title: "Grunt",
          message: "Sass Compiled Successfully.",
          duration: 2,
          max_jshint_notifications: 1
        }
      },
      autoprefixer:{
        options:{
          title: "Grunt",
          message: "CSS Autoprefixed Successfully.",
          duration: 2,
          max_jshint_notifications: 1
        }
      }
    },
    watch: {
      sass: {
        files: ['<%= project.css %>/**/*.{scss,sass}'],
        tasks: ['sass:dev','notify:sass']
      },
      autoprefixer: {
        files: ['<%= project.app %>/styles.css'],
        tasks: ['autoprefixer', 'notify:autoprefixer']
      }
    },
    browserSync: {
      dev: {
        bsFiles: {
          src : [
              '<%= project.app %>/styles.css',
              '<%= project.app %>/**/*.js',
              '<%= project.app %>/**/*.{png,jpg,jpeg,gif,webp,svg}',
              '<%= project.app %>/**/*.{php,html}'
          ]
        },
        options: {
          proxy: 'localhost:8888', //our PHP server
          port: 8080, // our new port
          open: true,
          watchTask: true
        }
      }
    }
  });
  
  // Default task(s).
  grunt.registerTask('default', [
    'sass:dev',
    'autoprefixer',
    'browserSync',
    'watch'
  ]);
  grunt.registerTask('build', [
    'sass:build',
    'autoprefixer',
  ]);
};