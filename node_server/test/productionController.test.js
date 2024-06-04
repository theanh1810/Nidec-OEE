require('dotenv').config({ path: `${__dirname}/../.env` })
const { sqlConnect } = require('../app/models/model')
const { start } = require('../app/services/changeShiftService')
const productionController = require('../app/controllers/express/productionController')

;(async () => {
    await sqlConnect()
    await start()

    await productionController.produce({
        body: { machineId: 1 }
    })

    // await productionController.qc({
    //     body: {
    //         quantity: 1,
    //         planId: 3
    //     }
    // })
})()