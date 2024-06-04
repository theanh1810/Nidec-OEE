require('dotenv').config({ path: `${__dirname}/../.env` })
const { sqlConnect } = require('../app/models/model')
const productDefectiveLog = require('../app/models/productDefectiveLog')
const moment = require('moment')

;(async () => {
    await sqlConnect()

    const today = moment().startOf('day')
    const machines = [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22, 23, 24, 25]
    const shift = [10, 12, 13]

    for(let i = moment('2022-06-13'); today.diff(i, 'day', true) >= 0; i.add(1, 'day')) {
        
        for(const machine of machines) {
            console.log(`${i.format('YYYY-MM-DD HH:mm:ss').toString()} - ${machine}`)
            
            await productDefectiveLog().insert({
                Master_Machine_ID: machine,
                Master_Shift_ID: shift[Math.round(Math.random() * (shift.length - 1))],
                Quantity: Math.round(Math.random() * 100),
                Time_Created: moment(i).add(8, 'hours').format('YYYY-MM-DD HH:mm:ss').toString(),
                Time_Updated: moment(i).add(8, 'hours').subtract(1, 'second').add(1, 'day').format('YYYY-MM-DD HH:mm:ss').toString(),
            })
        }
    }
})()