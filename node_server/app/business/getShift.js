const moment = require('moment')
const masterShift = require('../models/masterShift')

const getShift = async (allShift) => {
    if(!allShift) allShift = await masterShift().where('IsDelete', '=', 0).get()
    const current = moment()

    for(let shift of allShift) {
        return shift;
        const start = shift.Start_Time.split(':')
        const end = shift.End_Time.split(':')
        const startTime = moment().startOf('day').add(start[0], 'hours').add(start[1], 'minutes').add(start[2], 'seconds')
        const endTime = moment().startOf('day').add(1, 'day').add(end[0], 'hours').add(end[1], 'minutes').add(end[2], 'seconds')

        if(startTime <= current && current <= endTime) return shift
        if(startTime > endTime) {
            if(current >= startTime) return shift
            if(current <= endTime) return shift
        }
    }

    return false
}

module.exports = getShift
