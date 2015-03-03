module.exports = (grunt) ->

  deploy = grunt.option 'deploy'
  pkg = grunt.file.readJSON 'package.json'
  db = if deploy then pkg.dbConfig.remote else pkg.dbConfig.local

  themePath = pkg.themePath
  themePathLibrary = themePath + 'library/'
  baseDestURL = '/'

  auth =
    host: 'www.site-name.com',
    port: 21
    authKey: 'key1'

  exclusions = [
    '.DS_Store'
    '**/.DS_Store'
    '.git'
  ]

  lessfiles = {}
  uglifyfiles = {}
  mainJS = themePathLibrary + 'build/main.js'

  lessfiles[themePathLibrary + 'build/style-base.css'] = themePathLibrary + 'less/style-base.less';
  lessfiles[themePathLibrary + 'build/style.css'] = themePathLibrary + 'less/style.less';
  uglifyfiles[mainJS] = [mainJS];

  grunt.initConfig
    pkg: pkg

    exec:
      rsync: "rsync -avz -e 'ssh -p 2222' --exclude-from 'rsync-exclude-list.txt' ./ username@ip:public_html",
      openChrome: "open -a /Applications/Google\\ Chrome.app " + host,
      openSublime: "open -a /Applications/Sublime\\ Text\\ 2.app ."

    browserify:
      main:
        src: themePathLibrary + 'js/main.js'
        dest: themePathLibrary + 'build/main.js'
        options:
          shim:
            jQuery:
              path: themePathLibrary + 'js/vendor/jquery.min.js'
              exports: '$'
          debug: !deploy

    jshint:
      files:
        src: [ themePathLibrary + 'js/modules/**/*.js' ]

    less:
      main:
        options:
          paths: [
            themePathLibrary + 'less'
          ]
          dumpLineNumbers: if deploy then false else 'all'
          sourceMap: !deploy
          outputSourceFiles: !deploy
          compress: deploy
          sourceMapFilename: if deploy then false else themePathLibrary + 'build/style.css.map'
          sourceMapBasepath: if deploy then false else themePathLibrary + 'build/'
        files: lessfiles

    replace:
      wpconfig:
        options:
          patterns: [
            { match: 'dbname', replacement: db.dbname }
            { match: 'dbuser', replacement: db.dbuser }
            { match: 'dbpassword', replacement: db.dbpassword }
            { match: 'dbhost', replacement: db.dbhost }
          ]
        files: [
          {
              expand: true
              flatten: true
              src: ['wp-content/wp-config.php']
              dest: ''
          }
        ]

    uglify:
      main:
        files: uglifyfiles

    watch:
      options:
        livereload: true,
      less:
        files: themePathLibrary + "less/**/*.less"
        tasks: ['less']
      jshint:
        files: [
          themePathLibrary + 'js/**/*.js'
          '!' + themePathLibrary + 'js/*.min.js'
          '!' + themePathLibrary + 'js/vendor/*.js'
        ]
        tasks: ['jshint']
      browserify:
        files: [
            themePathLibrary + 'js/**/*.js'
            '!' + themePathLibrary + 'js/vendor/*.js'
        ]
        tasks: ['browserify']
      wpconfig:
        files: 'wp-content/wp-config.php'
        tasks: ['replace:wpconfig']
      php:
        files: themePath + '**/*.php'
      tests:
        files: [
          themePathLibrary + "js/tests/**/*.js"
        ]
        tasks: ['mochaTest']

    'ftp-deploy':
      theme_library:
        auth: auth
        src: themePathLibrary
        dest: baseDestURL + themePathLibrary
        exclusions: exclusions
      theme_php:
        auth: auth
        src: themePath
        dest: baseDestURL + themePath
        exclusions: exclusions.concat ['library']
      wordpress:
       auth: auth
        src: 'wordpress/'
        dest: baseDestURL + 'wordpress/'
        exclusions: exclusions.concat([
          'wp-content'
        ])
      top_level:
        auth: auth
        src: '.'
        dest: baseDestURL
        exclusions: exclusions.concat([
          'wp-content'
          'wordpress'
          'node_modules'
          '.ftppass'
        ])
      repo:
        auth: auth
        src: '.'
        dest: baseDestURL
        exclusions: exclusions.concat([
          'wordpress'
          'node_modules'
          './wp-content/plugins'
          './wp-content/uploads'
        ])

  require('matchdep')
  .filterDev('grunt-*')
  .forEach grunt.loadNpmTasks

  grunt.registerTask 'default', 'build the files', () ->

    tasks = [
      'replace:wpconfig'
      'jshint'
      'less'
      'browserify'
    ]

    if deploy
      tasks.push 'uglify'

      # tasks.push 'exec:rsync'

      if deploy.indexOf('wordpress') isnt -1
        tasks.push 'ftp-deploy:wordpress'
      if deploy.indexOf('php') isnt -1
        tasks.push 'ftp-deploy:theme_php'
      if deploy.indexOf('library') isnt -1
        tasks.push 'ftp-deploy:theme_library'
      if deploy.indexOf('top') isnt -1
        tasks.push 'ftp-deploy:top_level'
      if deploy.indexOf('repo') isnt -1
        tasks.push 'ftp-deploy:repo'

    else
      tasks.push 'exec:openSublime'
      tasks.push 'exec:openChrome'
      tasks.push 'watch'

    grunt.task.run tasks
