const route = require('express').Router()
const shiftMidleware = require('../../app/middleware/shift')
const shiftController = require('../../app/controllers/express/shiftController')

route.use(shiftMidleware)

route.get('/', shiftController.index)

module.exports = route