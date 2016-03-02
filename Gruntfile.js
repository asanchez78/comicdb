'use_strict';

module.exports = function(grunt) {
  require('load-grunt-tasks')(grunt);

  // Project configuration.
  grunt.initConfig({
    pkg: grunt.file.readJSON('package.json'),
    project: {
      app: ['source/'],
      build: ['build/'],
      css: ['<%= project.app %>/styles'],
      scripts: ['<%= project.app %>/scripts'],
      components: ['<%= project.app %>/modules']
    },
    // Sass -> CSS
    sass: {
      dev: {
        options: {
          style: 'expanded'
        },
        files: {
            "<%= project.build %>/styles.css": "<%= project.css %>/styles.scss"
        }
      },
      build: {
        options: {
          style: 'expanded',
          sourcemap: 'none'
        },
        files: {
            "<%= project.build %>/styles.css": "<%= project.css %>/styles.scss"
        }
      },
    },
    // JS -> Concat
    concat: {
      options: {
        separator: ';',
        sourceMap: true,
        sourceMapStyle: 'link'
      },
      dist: {
        src: ['<%= project.app %>/scripts/**/*.js', '<%= project.components %>/**/*.js', '!<%= project.app %>/scripts/**/*.min.js'],
        dest: '<%= project.build %>/scripts/app.js'
      },
    },
    // JS Minify
    uglify: {
      options: {
        sourceMap: true,
        sourceMapIn: '<%= project.build %>/scripts/app.js.map',
        screwIE8: true
      },
      dist: {
        files: {
          '<%= project.build %>/scripts/app.min.js': ['<%= concat.dist.dest %>']
        }
      }
    },
    // JS Error checking
    jshint: {
      files: ['Gruntfile.js', '<%= project.app %>/scripts/comicdb.js', '<%= project.components %>/**/*.js'],
      options: {
        // options here to override JSHint defaults
        globals: {
          jQuery: true,
          console: true,
          module: true,
          document: true
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
          "<%= project.build %>/styles.css": "<%= project.build %>/styles.css"
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
      js:{
        options:{
          title: "Grunt",
          message: "JS linted, compiled, and minified successfully.",
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
      },
      php:{
        options:{
          title: "Grunt",
          message: "PHP Updated Successfully.",
          duration: 2,
          max_jshint_notifications: 1
        }
      },
      images:{
        options:{
          title: "Grunt",
          message: "Images Copied Successfully.",
          duration: 2,
          max_jshint_notifications: 1
        }
      }
    },
    // Copies remaining files to places other tasks can use
    copy: {
      main: {
        expand: true,
        cwd: '<%= project.app %>/',
        src: ['**/*.php', 'scripts/jquery-2.2.0.min.js', 'assets/**/*', 'images/**/*', 'favicon.ico'],
        dest: '<%= project.build %>/',
      }
    },
    sync: {
      php: {
        files: [{
          cwd: '<%= project.app %>/', 
          src: ['**/*.php'],
          dest: '<%= project.build %>/'
        }],
      },
      images: {
        files: [{
          cwd: '<%= project.app %>/', 
          src: ['assets/**/*'],
          dest: '<%= project.build %>/'
        }],
      }
    },
    // Empties folders to start fresh
    clean: {
      main: {
        files: [{
          dot: true,
          src: [
            '<%= project.build %>/{,*/}*'
          ]
        }]
      }
    },
    watch: {
      sass: {
        files: ['<%= project.css %>/**/*.{scss,sass}','<%= project.components %>/**/*.{scss,sass}'],
        tasks: ['sass:dev','notify:sass']
      },
      js: {
        files: ['<%= jshint.files %>'],
        tasks: ['jshint','concat','uglify','notify:js']
      },
      autoprefixer: {
        files: ['<%= project.build %>/styles.css'],
        tasks: ['autoprefixer', 'notify:autoprefixer']
      },
      // Sync tasks for PHP and Images
      php: {
        files: ['<%= project.app %>/**/*.php'],
        tasks: ['sync:php', 'notify:php']
      },
      images: {
        files: ['<%= project.app %>/assets/**/*'],
        tasks: ['sync:images', 'notify:images']
      }
    },
    browserSync: {
      dev: {
        bsFiles: {
          src : [
              '<%= project.build %>/styles.css',
              '<%= project.build %>/app.min.js',
              '<%= project.build %>/**/*.{png,jpg,jpeg,gif,webp,svg}',
              '<%= project.build %>/**/*.{php,html}'
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
    'clean',
    'copy:main',
    'sass:dev',
    'jshint',
    'concat',
    'uglify',
    'autoprefixer',
    'browserSync',
    'watch'
  ]);
  grunt.registerTask('build', [
    'sass:build',
    'concat',
    'uglify',
    'autoprefixer',
  ]);
};