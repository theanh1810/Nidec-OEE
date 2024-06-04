const { getStartOfDay } = require("../../configs/app.config");
const moment = require("moment");

const checkRuntimeHistory = (
    lastRecord,
    machineStatus,
    shift,
    planId = null
) => {
    const startOfDay = getStartOfDay();
    const {
        IsRunning,
        Master_Shift_ID,
        Time_Updated,
        Master_Status_ID,
        Command_Production_Detail_ID,
    } = lastRecord;


    if (
        planId &&
        Command_Production_Detail_ID != planId
    ) {
        return true;
    }

    const isRunningNew = machineStatus != 1 ? 0 : 1;

    if (IsRunning != isRunningNew) return true;

    const status = getMachineStatus(Master_Status_ID);
    if (status != machineStatus) return true;

    if (Master_Shift_ID != shift.ID) return true;

    if (
        startOfDay.diff(moment(Time_Updated), "hours", true) > 0 &&
        startOfDay.diff(moment(), "hours", true) <= 0
    ) {
        return true;
    }

    if (
        moment(Time_Updated).format("DD").toString() !==
        moment().format("DD").toString()
    ) {
        return true;
    }

    return false;
};

const getMachineStatus = (machineStatusId) => {
    switch (Number(machineStatusId)) {
        case 0:
            return 1;
        case 7:
        case 8:
        case 9:
        case 10:
            return 2;
        case 1:
        case 2:
        case 3:
        case 4:
        case 5:
        case 6:
            return 3;
        default:
            return 4;
    }
};

module.exports = checkRuntimeHistory;
