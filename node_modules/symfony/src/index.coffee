terminal = require('child_process').spawn('bash')
coffeescript = require 'coffee-script'


module.exports = class CoffeeScriptCompiler
  brunchPlugin: yes

  constructor: (@config) ->
    console.log 'construct'

  onCompile: (changedFiles) =>
    console.log 'on compile'
