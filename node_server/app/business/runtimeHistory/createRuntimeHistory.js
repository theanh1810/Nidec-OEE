const moment = require('moment')
const runtimeHistory = require('../../models/runtimeHistory')

const createRuntimeHistory = async (shift, machineId, machineStatus, planId = 0) => {
    const current = moment().format('YYYY-MM-DD HH:mm:ss').toString()

    const getStatusDefault = (machineStatus) => {
        switch(Number(machineStatus)) {
            case 1:
                return 0;
            case 2:
                return 10; // others(stop not error)
            case 3:
                return 6; // others(stop error)
            default:
                return 17;
        }
    }

    const runtime = {
        Master_Shift_ID: shift.ID,
        Master_Machine_ID: machineId,
        Master_Status_ID: getStatusDefault(machineStatus),
        IsRunning: machineStatus != 1 ? 0 : 1,
        Time_Created: current,
        Time_Updated: current,
        Command_Production_Detail_ID: planId
    }

    const runtimeHistoryId = await runtimeHistory().insert(runtime)

    runtime.ID = runtimeHistoryId

    return runtime
}

module.exports = createRuntimeHistory
