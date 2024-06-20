const moment = require("moment");
const runtimeHistory = require("../../models/runtimeHistory");
const runtimeHistoryModel = require("../../models/RuntimeHistory.model");
const getRuntimeHistory = require("../../business/runtimeHistory/getRuntimeHistory");
const { Op } = require("sequelize");
const { io } = require("../../../server/io");
const {
    getAllStatus,
} = require("../../services/changeShiftService");

const createRuntimeHistory = async (
    shift,
    machineId,
    machineStatus,
    planId = 0
) => {
    console.log("create timeline");
    try {
        const current = moment();

        const getStatusDefault = (machineStatus) => {
            switch (Number(machineStatus)) {
                case 1:
                    return 0;
                case 2:
                    return 10; // others(stop not error)
                case 3:
                    return 6; // others(stop error)
                default:
                    return 17;
            }
        };
        const allStatus = await getAllStatus();

        const runtime = {
            Master_Shift_ID: shift.ID,
            Master_Machine_ID: machineId,
            Master_Status_ID: getStatusDefault(machineStatus),
            IsRunning: machineStatus != 1 ? 0 : 1,
            Time_Created: current.format("YYYY-MM-DD HH:mm:ss").toString(),
            Time_Updated: current.format("YYYY-MM-DD HH:mm:ss").toString(),
            Command_Production_Detail_ID: planId,
        };

        const runtimeHistoryId = await runtimeHistory().insert(runtime);

        const runtimeHistories = await runtimeHistoryModel.findOne({
            where: {
                Master_Machine_ID: machineId,
                ID: { [Op.lt]: runtimeHistoryId },
            },
            order: [["ID", "DESC"]],
            limit: 1,
        });

        if (runtimeHistories) {
            const status = allStatus?.find(
                (a) => a.ID == runtimeHistories.Master_Status_ID
            );
            runtimeHistories.Duration = current.diff(
                moment(runtimeHistories.Time_Created),
                "minutes"
            );
            runtimeHistories.Time_Updated = current
                .format("YYYY-MM-DD HH:mm:ss")
                .toString();
            await runtimeHistories.save();
            io.emit(`machine-${machineId}`, {
                timeline: {
                    isCreated: false,
                    data: {
                        Time_Created: runtimeHistories.Time_Created,
                        Time_Updated: current
                            .format("YYYY-MM-DD HH:mm:ss")
                            .toString(),
                        IsRunning: machineStatus,
                        Master_Status_ID: runtimeHistories.Master_Status_ID,
                        Duration: current.diff(
                            moment(runtimeHistories.Time_Created),
                            "minutes"
                        ),
                        StatusName: status?.Name,
                        MasterStatus: {
                            Name: status?.Name,
                        },
                    },
                },
            });
        }

        runtime.ID = runtimeHistoryId;
        return runtime;
    } catch (err) {
        console.log(err);
    }
};

module.exports = createRuntimeHistory;
