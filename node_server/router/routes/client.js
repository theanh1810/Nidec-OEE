const route = require('express').Router()
const clientMidleware = require('../../app/middleware/client')
const clientController = require('../../app/controllers/express/clientController')

route.use(clientMidleware)

route.get('/get-clients', clientController.index)

module.exports = route