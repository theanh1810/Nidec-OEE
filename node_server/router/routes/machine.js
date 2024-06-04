const route = require('express').Router()
const machineMiddleware = require('../../app/middleware/machine')
const machineController = require('../../app/controllers/express/machineController')

route.use(machineMiddleware)

route.get('/', machineController.getMachine)
route.get('/machine-card-init', machineController.initMachine)
route.get('/machine-production-init', machineController.initMachineProduction)
route.get('/runtime-history-init', machineController.initRuntime)

module.exports = route