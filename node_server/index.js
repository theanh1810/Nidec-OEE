require('dotenv').config()
const { sqlConnect }  = require('./app/models/model')
const changeShiftService = require('./app/services/changeShiftService')
const machineLoopService = require('./app/services/machineLoopService')

;(async () => {
    try {
        await sqlConnect()
        await changeShiftService.start()
        require('./app/configs')
        require('./router/routes')
        require('./server/express').start()
        require('./server/socket').start()
        machineLoopService.start()
    } catch(err) {
        console.log(err)
    }
})()
