require('dotenv').config({ path: `${__dirname}/../.env` })
const { sqlConnect } = require('../app/models/model')
const getShift = require('../app/business/getShift')

;(async () => {
    await sqlConnect()
    await getShift()
    console.log(await getShift())
})()