module.exports = function(grunt) {

  // Project configuration.
  grunt.initConfig({

    pkg: grunt.file.readJSON('package.json'),

    less: {
      dist: {
        options: {
          paths: ["css"],
          sourceMap: true,
          sourceMapFilename: 'css/style.css.map',
        },
        files: {
          "css/style.css": "styles/style.less"
        }
      }
    },

    autoprefixer: {
      options: {
        browsers: ['last 2 version', 'ie 8', 'ie 9', 'ie 10']
      },
      no_dest: {
        src: 'css/style.css'
      }
    },

    csso: {
      compress: {
        options: {
          report: 'gzip'
        },
        files: {
          'css/style.css': ['css/style.css']
        }
      }
    },

    watch: {
      css: {
        files: ['styles/**/**/**.less'],
        tasks: ['less:dist', 'autoprefixer:no_dest'],
        options: {
          livereload: true
        }
      }
    },

    imagemin: {
      png: {
        options: {
          optimizationLevel: 7
        },
        files: [
          {
            // Set to true to enable the following options…
            expand: true,
            // cwd is 'current working directory'
            cwd: 'img/',
            src: ['**/*.png'],
            // Could also match cwd line above. i.e. project-directory/img/
            dest: 'img/',
            ext: '.png'
          }
        ]
      },
      svg: {
        options: {
          optimizationLevel: 7
        },
        files: [
          {
            // Set to true to enable the following options…
            expand: true,
            // cwd is 'current working directory'
            cwd: 'img/',
            src: ['**/*.svg'],
            // Could also match cwd line above. i.e. project-directory/img/
            dest: 'img/',
            ext: '.svg'
          }
        ]
      }
    },

    favicons: {
      options: {},
      icons: {
        src: 'img/favicon/original/logo.png',
        dest: 'img/favicon/'
      }
    },

    node_version: {
      options: {
        alwaysInstall: false,
        errorLevel: 'fatal',
        globals: [],
        maxBuffer: 200*1024,
        nvm: true,
        override: ''
      }
    }
  });

  // Load plugins
  grunt.loadNpmTasks('grunt-contrib-less');
  grunt.loadNpmTasks('grunt-autoprefixer');
  grunt.loadNpmTasks('grunt-csso');
  grunt.loadNpmTasks('grunt-contrib-watch');
  grunt.loadNpmTasks('grunt-contrib-imagemin');
  grunt.loadNpmTasks('grunt-favicons');
  grunt.loadNpmTasks('grunt-node-version');

  // deploy task for when your ready to go to production
  grunt.registerTask('prep', ['node_version', 'less:dist', 'autoprefixer:no_dest', 'csso:compress', 'imagemin']);

  // Default task(s).
  grunt.registerTask('default', ['node_version', 'less:dist', 'autoprefixer:no_dest', 'csso:compress', 'imagemin']);
};