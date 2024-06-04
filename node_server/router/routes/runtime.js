const route = require('express').Router()
const runtimeMidleware = require('../../app/middleware/runtime')
const runtimeController = require('../../app/controllers/express/runtimeController')

route.use(runtimeMidleware)

route.get('/', runtimeController.index)

module.exports = route