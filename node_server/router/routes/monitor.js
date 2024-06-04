const route = require('express').Router()
const monitorMiddleware = require('../../app/middleware/monitor')
const monitorController = require('../../app/controllers/express/monitorController')

route.use(monitorMiddleware)

route.get('/', monitorController.index)
route.get('/calculator', monitorController.calculator)

module.exports = route