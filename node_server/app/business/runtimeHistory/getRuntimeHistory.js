const { getStartOfDay } = require("../../configs/app.config");
const moment = require("moment");
const runtimeHistory = require('../../models/runtimeHistory')

const getRuntimeHistory = async (machineId, machineStatus, planId = null) => {
    const current = moment();
    const startOfDay = getStartOfDay();
    const runtime = runtimeHistory().where('Master_Machine_ID', '=', machineId)
    if (machineStatus) {
        runtime.where('IsRunning', '=',  machineStatus == 1 ? 1 : 0)
    }
    if(machineStatus == 3)
    {
        runtime.where('Master_Status_ID', '>=',  1)
        runtime.where('Master_Status_ID', '<=',  6)
    }
    if(machineStatus == 2)
    {
        runtime.where('Master_Status_ID', '>=',  7)
        runtime.where('Master_Status_ID', '<=',  10)
    }
    if(machineStatus == 4)
    {
        runtime.where('Master_Status_ID', '=',  17)
    }
    if(planId)
    {
        runtime.where('Command_Production_Detail_ID', '=', planId)
    }
    if(startOfDay.diff(current, 'minutes', true) > 0) {
        runtime.where('Time_Created', '>=', startOfDay.subtract(1, 'day').format('YYYY-MM-DD HH:mm:ss').toString())
    } else {
        runtime.where('Time_Created', '>=', startOfDay.format('YYYY-MM-DD HH:mm:ss').toString())
    }

    return await runtime.get()
};

module.exports = getRuntimeHistory;
