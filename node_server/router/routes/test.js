const route = require('express').Router()
const testMiddleware = require('../../app/middleware/test')
const controller = require('../../app/controllers/express/controller')

route.use(testMiddleware)

route.get('/', controller.index)
route.get('/model', controller.model)
route.get('/send', controller.sendMessage)
route.get('/shift', controller.getShift)
route.get('/reset', controller.reset)

module.exports = route