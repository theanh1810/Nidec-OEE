require('dotenv').config({ path: `${__dirname}/../.env` })
const { sqlConnect } = require('../app/models/model')
const productionLog = require('../app/models/productionLog')
const moment = require('moment')

;(async () => {
    await sqlConnect()

    await productionLog().insert({
        Command_Production_Detail_ID: 1,
        Master_Shift_ID: 1,
        Master_Machine_ID: 1,
        Time_Created: '2022-07-08 10:00:00:000',
        Time_Updated: '2022-07-08 10:00:00:000'
    })
    
})()