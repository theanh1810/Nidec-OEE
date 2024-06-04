require('dotenv').config({ path: `${__dirname}/../.env` })
const { sqlConnect } = require('../app/models/model')
const getPlannedTime = require('../app/business/getPlannedTime')

;(async () => {
    await sqlConnect()
    console.log(await getPlannedTime('shift'))
})()