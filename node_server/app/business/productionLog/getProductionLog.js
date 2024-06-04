const { getStartOfDay } = require('../../configs/app.config')
const productionLog = require('../../models/productionLog')
const moment = require('moment')

const getProductionLog = async (machineId, planId = null) => {
    const current = moment()
    const startOfDay = getStartOfDay()

    const productionLogs = productionLog().where('Master_Machine_ID', '=', machineId)
    if(planId)
    {
        productionLogs.where('Command_Production_Detail_ID', '=', planId)
    }
    if(startOfDay.diff(current, 'minutes', true) > 0) {
        productionLogs.where('Time_Created', '>=', startOfDay.subtract(1, 'day').format('YYYY-MM-DD HH:mm:ss').toString())
    } else {
        productionLogs.where('Time_Created', '>=', startOfDay.format('YYYY-MM-DD HH:mm:ss').toString())
    }

    return await productionLogs.get()
}

module.exports = getProductionLog
