const moment = require('moment')
const { getCurrentShift } = require('../services/changeShiftService')
const { getStartOfDay } = require('../configs/app.config')

const getShiftPlannedTime = async (current) => {
    if(!current) current = moment()
    const shift = await getCurrentShift()
    const start = shift.Start_Time.split(':')
    const end = shift.End_Time.split(':')
    
    const startTime = moment().startOf('day').add(start[0], 'hours').add(start[1], 'minutes').add(start[2], 'seconds')
    const endTime = moment().startOf('day').add(end[0], 'hours').add(end[1], 'minutes').add(end[2], 'seconds')

    if(startTime <= current && current <= endTime) return current.diff(startTime, 'seconds') / 60
    if(startTime > endTime) {
        if(current >= startTime) return current.diff(startTime, 'seconds') / 60
        if(current <= endTime) return current.diff(startTime.subtract(1, 'day'), 'seconds') / 60
    }
}

const getDayPlannedTime = async (current) => {
    if(!current) current = moment()
    const startOfDay = getStartOfDay()
    const duration = current.diff(startOfDay, 'seconds') / 60

    if(duration >= 0) return duration
    return current.diff(startOfDay.subtract(1, 'day'), 'seconds') / 60
}

const getPlannedTime = (type) => {
    switch(type) {
        case 'shift':
            return getShiftPlannedTime
        case 'day':
            return getDayPlannedTime
        default:
            throw new Error(`'${type}' is invalid params, 'day' or 'shift'`)
    }
}

module.exports = getPlannedTime