'use strict'

module.exports = (grunt) ->

  require('time-grunt')(grunt)
  require('jit-grunt')(grunt)

  grunt.initConfig
    pkg: grunt.file.readJSON('package.json')


    # Sass Task
    sass:
      options:
        style: 'expanded'
        precision: 10
        update: true

      dev:
        expand: true
        cwd: 'src/scss'
        src: ['*.{scss,sass}', '!_*.{scss,sass}']
        dest: 'css'
        ext: '.css'


    # Watch Task
    watch:
      options:
        livereload: true
        dateFormat: (time) ->
          grunt.log.writeln('Grunt has finished in ' + time + 'ms!')
          grunt.log.writeln('Waiting...')
        event: ['all']
        interrupt: true

      configFiles:
        files: ['Gruntfile.coffee']
        options:
          reload: true

      sass:
        files: '**/*.{scss,sass}'
        tasks: ['sass']

      js:
        files: '**/*.js'

  # Default Task
  grunt.registerTask 'default', [
    'sass',
    'watch'
  ]