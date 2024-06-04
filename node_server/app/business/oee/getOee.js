const oeeDay = require('../../models/oeeDay')
const oeeShift = require('../../models/oeeShift')
const { getStartOfDay } = require('../../configs/app.config') 
const { getCurrentShift } = require('../../services/changeShiftService')
const moment = require('moment')

const getOeeByDay = async (machineId) => {
    const startOfDay = getStartOfDay()
    const oee = oeeDay().where('Master_Machine_ID', '=', machineId)

    if(moment().diff(startOfDay, 'hours', true) < 0) {
        oee.where('Time_Created', '>=', startOfDay.subtract(1, 'day').format('YYYY-MM-DD HH:mm:ss').toString())
    } else {
        oee.where('Time_Created', '>=', startOfDay.format('YYYY-MM-DD HH:mm:ss').toString())
    }

    return await oee.first()
}

const getOeeByShift = async (machineId) => {
    const shift = await getCurrentShift()

    if(shift) {
        const current = moment()
        const start = shift.Start_Time.split(':')
        const end = shift.End_Time.split(':')
        const startTime = moment().startOf('day').add(start[0], 'hours').add(start[1], 'minutes').add(start[2], 'seconds')
        const endTime = moment().startOf('day').add(end[0], 'hours').add(end[1], 'minutes').add(end[2], 'seconds')

        const oee = oeeShift().where('Master_Shift_ID', '=', shift.ID)
                              .where('Master_Machine_ID', '=', machineId)

        if(startTime <= current && current <= endTime) oee.where('Time_Created', '>=', moment().startOf('day').format('YYYY-MM-DD HH:mm:ss').toString())
        if(startTime > endTime) {
            if(current >= startTime) oee.where('Time_Created', '>=', moment().startOf('day').format('YYYY-MM-DD HH:mm:ss').toString())
            if(current <= endTime) oee.where('Time_Created', '>=', moment().subtract(1, 'day').format('YYYY-MM-DD HH:mm:ss').toString())
        }

        return await oee.first()
    }
}

const getOee = async (type, machineId) => {
    switch(type) {
        case 'shift':
            return await getOeeByShift(machineId)
        case 'day':
            return await getOeeByDay(machineId)
        default:
            throw new Error(`'${type}' is invalid params, 'day' or 'shift'`)
    }
}

module.exports = getOee