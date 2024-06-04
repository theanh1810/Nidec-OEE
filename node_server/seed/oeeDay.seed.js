require('dotenv').config({ path: `${__dirname}/../.env` })
const { sqlConnect } = require('../app/models/model')
const moment = require('moment')
const oeeDay = require('../app/models/oeeDay')

;(async () => {
    await sqlConnect()

    const today = moment().startOf('day')
    const machines = [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22, 23, 24, 25]

    for(let i = moment('2022-06-13'); today.diff(i, 'day', true) >= 0; i.add(1, 'day')) {
        
        for(const machine of machines) {
            console.log(`${i.format('YYYY-MM-DD HH:mm:ss').toString()} - ${machine}`)
            const date = i.format('YYYY-MM-DD HH:mm:ss').toString()
            const A = Math.round(Math.random() * 10000) / 100
            const P = Math.round(Math.random() * 10000) / 100
            const Q = Math.round(Math.random() * 10000) / 100
            const Oee = Math.round((A * P * Q) / 100) / 100
            
            await oeeDay().insert({
                Master_Machine_ID: machine,
                Oee,
                A,
                P,
                Q,
                Time_Created: moment(date).add(8, 'hours').format('YYYY-MM-DD HH:mm:ss').toString(),
                Time_Updated: moment(date).add(8, 'hours').subtract(1, 'second').add(1, 'day').format('YYYY-MM-DD HH:mm:ss').toString(),
            })
        }
    }
})()