const middleware = {}

middleware['authenticated'] = require('..\\middleware\\authenticated.js')
middleware['authenticated'] = middleware['authenticated'].default || middleware['authenticated']

middleware['guest'] = require('..\\middleware\\guest.js')
middleware['guest'] = middleware['guest'].default || middleware['guest']

export default middleware
