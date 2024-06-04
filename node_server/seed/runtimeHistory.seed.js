require('dotenv').config({ path: `${__dirname}/../.env` })
const { sqlConnect } = require('../app/models/model')
const runtimeHistory = require('../app/models/runtimeHistory')
const moment = require('moment')

;(async () => {
    await sqlConnect()

    const today = moment().startOf('day')
    const machines = [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22, 23, 24, 25]
    const machineStatus = [2, 3]
    const error = [1, 2, 3, 4, 5, 6]
    const notError = [7, 8, 9, 10, 11, 12, 13, 14, 15]
    const shift = [10, 12, 13]

    const getStatus = machineStatus => {
        switch(machineStatus) {
            case 2:
                return notError[Math.round(Math.random() * (notError.length - 1))]
            case 3:
                return error[Math.round(Math.random() * (error.length - 1))]
        }
    }

    for(let i = moment('2022-06-13'); today.diff(i, 'day', true) >= 0; i.add(1, 'day')) {
        
        for(const machine of machines) {
            console.log(`${i.format('YYYY-MM-DD HH:mm:ss').toString()} - ${machine}`)
            let maxduration = 0
            let leftTime = 1440
            let start = moment(i)

            while(leftTime > 10) {
                console.log('while')
                start.add(Math.floor(Math.random() * leftTime), 'minutes')
                maxduration = moment(i).endOf('day').diff(start, 'minutes', true)
                const duration = Math.floor(Math.random() * maxduration)
                const end = start.add(duration, 'minutes')
                leftTime = moment(i).endOf('day').diff(end, 'minutes', true)
                const isRunning = machineStatus[Math.round(Math.random() * (machineStatus.length - 1))]

                await runtimeHistory().insert({
                    Production_Log_ID: Math.round(Math.random() * 100) + 1,
                    Master_Shift_ID: shift[Math.round(Math.random() * (shift.length - 1))],
                    Master_Machine_ID: machine,
                    Master_Status_ID: getStatus(isRunning),
                    IsRunning: isRunning,
                    Duration: duration,
                    Time_Created: start.format('YYYY-MM-DD HH:mm:ss').toString(),
                    Time_Updated: end.format('YYYY-MM-DD HH:mm:ss').toString()
                })
            }
        }
    }


    // await runtimeHistory().insert({
    //     Production_Log_ID: 1,
    //     Master_Shift_ID: 12,
    //     Master_Machine_ID: 2,
    //     IsRunning: true,
    //     Duration: timeUpdated.diff(timeCreated, 'minutes', true),
    //     Time_Created: timeCreated.format('YYYY-MM-DD HH:mm:ss').toString(),
    //     Time_Updated: timeUpdated.format('YYYY-MM-DD HH:mm:ss').toString()
    // })
    
})()