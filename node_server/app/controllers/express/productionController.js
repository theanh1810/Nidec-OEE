const commandProductionDetail = require("../../models/commandProductionDetail");
const oeeDay = require("../../models/oeeDay");
const oeeShift = require("../../models/oeeShift");
const runtimeHistory = require("../../models/runtimeHistory");

const getOee = require("../../business/oee/getOee");
const getPlannedTime = require("../../business/getPlannedTime");
const getFullRunTime = require("../../business/getFullRuntime");
const getProductionLog = require("../../business/productionLog/getProductionLog");
const caculateOee = require("../../business/oee/calculateOee");
const createRuntimeHistory = require("../../business/runtimeHistory/createRuntimeHistory");
const getRuntimeHistory = require("../../business/runtimeHistory/getRuntimeHistory");
const checkRuntimeHistory = require("../../business/runtimeHistory/checkRuntimeHistory");
const calculateTPA = require("../../business/calculateTPA");

const { getCurrentShift, getAllStatus } = require("../../services/changeShiftService");
const moment = require("moment");
const { io } = require("../../../server/io");
const { timelineLogger } = require("../../providers/logger");
const masterBom = require('../../models/masterBom');

module.exports = {
    async runtime({ machineId, machineStatus }) {
        try {
            const emitData = { machineStatus };
            const current = moment();
            const shift = await getCurrentShift();
            const allStatus = await getAllStatus();
            const planIsRunning = await commandProductionDetail()
                .where("IsDelete", "=", 0)
                .where("Part_Action", "=", machineId)
                .where("Status", "=", 1)
                .get();

            const runtimeHistories = await getRuntimeHistory(machineId);
            if (runtimeHistories.length) {
                const lastRecord =
                    runtimeHistories[runtimeHistories.length - 1];

                if (checkRuntimeHistory(lastRecord, machineStatus, shift, planIsRunning[0]?.ID)) {

                    const runtime = await createRuntimeHistory(
                        shift,
                        machineId,
                        machineStatus,
                        planIsRunning[0]?.ID
                    );
                    if(runtime.Master_Status_ID)
                    {
                        runtime['MasterStatus'] = {
                            Name: 'Nguyên nhân khác'
                        }
                    }
                    emitData.timeline = {
                        isCreated: true,
                        data: runtime,
                    };
                } else {

                    const duration =
                        current.diff(
                            moment(lastRecord.Time_Created),
                            "seconds"
                        ) / 60;
                    lastRecord.Duration = duration;
                    await runtimeHistory()
                        .where("ID", "=", lastRecord.ID)
                        .update({
                            Duration:
                                current.diff(
                                    moment(lastRecord.Time_Created),
                                    "seconds"
                                ) / 60,
                            Time_Updated: current
                                .format("YYYY-MM-DD HH:mm:ss")
                                .toString(),
                        });

                    const H_Stop_Machine = Math.floor(duration / 60); // Giờ dừng máy
                    const M_Stop_Machine = Math.floor(
                        duration - H_Stop_Machine * 60
                    ); // Phút dừng máy
                    const S_Stop_Machine = Math.floor(
                        (duration - H_Stop_Machine * 60 - M_Stop_Machine) * 60 
                    ); // Giây dừng máy
                    const status = allStatus?.find(a=>a.ID == lastRecord.Master_Status_ID);
                    emitData.timeline = {
                        isCreated: false,
                        data: {
                            Time_Created: lastRecord.Time_Created,
                            Time_Updated: current
                                .format("YYYY-MM-DD HH:mm:ss")
                                .toString(),
                            IsRunning: machineStatus,
                            Master_Status_ID: lastRecord.Master_Status_ID,
                            Duration: `${H_Stop_Machine}:${M_Stop_Machine}:${S_Stop_Machine}`,
                            StatusName: status?.Name,
                            MasterStatus:{
                                Name: status?.Name
                            }
                        },
                    };
                }
            } else {
                const runtime = await createRuntimeHistory(
                    shift,
                    machineId,
                    machineStatus,
                    planIsRunning[0]?.ID
                );
                if(runtime.Master_Status_ID)
                {
                    runtime['MasterStatus'] = {
                        Name: 'Nguyên nhân khác'
                    }
                }
                emitData.timeline = {
                    isCreated: true,
                    data: runtime,
                };
            }

            if (planIsRunning.length) {
                const bom = await masterBom().where('Product_BOM_ID', '=', planIsRunning[0].Product_ID)
                .where('Mold_ID', '=', planIsRunning[0].Mold_ID)
                .first();

                emitData.production = await calculateTPA(planIsRunning, bom);
                const paramsDay = {
                    total: 0,
                    ng: 0,
                    runtime: 0,
                    netRuntime: 0,
                    plannedTime: 0,
                    fullRunTime: 0
                };
                const paramsShift = {
                    total: 0,
                    ng: 0,
                    runtime: 0,
                    netRuntime: 0,
                    plannedTime: 0,
                    fullRunTime: 0
                };
                const productionLogs = await getProductionLog(machineId,
                    planIsRunning[0]?.ID);
                const runtimes = runtimeHistories.filter(
                    (runtime) => runtime.IsRunning == 1 && runtime.Command_Production_Detail_ID == planIsRunning[0]?.ID
                );

                const fullRuntimes = runtimeHistories.filter(
                    (runtime) => runtime.IsRunning == 1
                );

                for (const productionLog of productionLogs) {
                    paramsDay.total += Number(productionLog.Total);
                    paramsDay.ng += Number(productionLog.NG);
                    paramsDay.netRuntime +=
                        (Number(productionLog.Total - (productionLog.NG ?? 0))/planIsRunning[0].Cavity_Real) *
                        ((Number(productionLog.Cycletime)/60));
                    if (productionLog.Master_Shift_ID == shift.ID) {
                        paramsShift.total += Number(productionLog.Total);
                        paramsShift.ng += Number(productionLog.NG);
                        paramsShift.netRuntime +=
                           (Number(
                                productionLog.Total - (productionLog.NG ?? 0)
                            )/planIsRunning[0].Cavity_Real) *
                            ((Number(productionLog.Cycletime)/60));
                    }
                }

                if (runtimes.length) {
                    for (const runtime of runtimes) {
                        paramsDay.runtime += Number(runtime.Duration);
                        if (runtime.Master_Shift_ID == shift.ID) {
                            paramsShift.runtime += Number(runtime.Duration);
                        }
                    }
                }

                if (fullRuntimes.length) {
                    for (const runtime of fullRuntimes) {
                        paramsDay.fullRunTime += Number(runtime.Duration);
                        if (runtime.Master_Shift_ID == shift.ID) {
                            paramsShift.fullRunTime += Number(runtime.Duration);
                        }
                    }
                }

                // paramsDay.fullRunTime = getFullRunTime(planIsRunning[0].Time_Real_Start);
                // paramsShift.fullRunTime = getFullRunTime(planIsRunning[0].Time_Real_Start);

                paramsDay.plannedTime = await getPlannedTime("day")(current);
                paramsShift.plannedTime = await getPlannedTime("shift")(
                    current
                );

                const resultOeeDay = caculateOee.call(paramsDay);
                const resultOeeShift = caculateOee.call(paramsShift);

                const _oeeDay = await getOee("day", machineId);
                const _oeeShift = await getOee("shift", machineId);

                if (_oeeDay) {
                    await oeeDay()
                        .where("ID", "=", _oeeDay.ID)
                        .update(resultOeeDay);
                } else {
                    await oeeDay().insert({
                        Master_Machine_ID: machineId,
                        Note: "runtime insert",
                        ...resultOeeDay,
                    });
                }
                if (_oeeShift) {
                    await oeeShift()
                        .where("ID", "=", _oeeShift.ID)
                        .update(resultOeeShift);
                } else {
                    await oeeShift().insert({
                        Master_Machine_ID: machineId,
                        Note: "runtime insert",
                        Master_Shift_ID: shift.ID,
                        ...resultOeeShift,
                    });
                }

                emitData.shift = resultOeeShift;
                emitData.day = resultOeeDay;
            }
            // console.log('runtime: machine: ', machineId, ', data:',   emitData)
            io.emit(`machine-${machineId}`, emitData);
        } catch (err) {
            timelineLogger.error(JSON.stringify(err));
        }
    },
};
