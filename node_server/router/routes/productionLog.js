const route = require('express').Router()
const productionLogMidleware = require('../../app/middleware/productionLog')
const productionLogController = require('../../app/controllers/express/productionLogController')

route.use(productionLogMidleware)

route.get('/', productionLogController.index)
route.get('/detail', productionLogController.detail)

module.exports = route