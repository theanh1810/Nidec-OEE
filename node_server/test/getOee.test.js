require('dotenv').config({ path: `${__dirname}/../.env` })
const { sqlConnect }  = require('../app/models/model')
const getOee = require('../app/business/getOee')
const oeeDay = require('../app/models/oeeDay')
const oeeShift = require('../app/models/oeeShift')

;(async () => {
    await sqlConnect()

    console.log(await getOee('shift', 1))
})()