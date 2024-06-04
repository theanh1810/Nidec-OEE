const route = require('express').Router()
const serviceMidleware = require('../../app/middleware/service')
const serviceController = require('../../app/controllers/express/serviceController')

route.use(serviceMidleware)

route.get('/machine', serviceController.getMachineService)

module.exports = route