const commandProductionDetail = require("../../models/commandProductionDetail");
const masterBom = require("../../models/masterBom");
const masterProduct = require("../../models/masterProduct");
const masterMold = require("../../models/masterMold");
const productionLog = require("../../models/productionLog");
const runtimeHistory = require("../../models/runtimeHistory");
const oeeDay = require("../../models/oeeDay");
const oeeShift = require("../../models/oeeShift");
const productDefectiveLog = require("../../models/productDefectiveLog");

const getProductionLog = require("../../business/productionLog/getProductionLog");
const getOee = require("../../business/oee/getOee");
const getPlannedTime = require("../../business/getPlannedTime");
const getFullRuntime = require("../../business/getFullRunTime");
const getRuntimeHistory = require("../../business/runtimeHistory/getRuntimeHistory");
const caculateOee = require("../../business/oee/calculateOee");
const createProductionLog = require("../../business/productionLog/createProductionLog");
const checkCondition = require("../../business/checkCondition");

const { getCurrentShift } = require("../../services/changeShiftService");
const {
    produceLogger,
    qcLogger,
    stopMachineLogger,
    errorMachineLogger,
    startPlanLogger,
    setupMachineLogger,
} = require("../../providers/logger");

module.exports = {
    async produce({ payload, io }) {
        produceLogger.info(payload);

        try {
            const { machineId, Plan_Id: id } = payload;
            const stt = 1;
            const planIsRunnings = await commandProductionDetail()
                .where("IsDelete", "=", 0)
                .where("Part_Action", "=", machineId)
                .where("Status", "=", 1)
                .get();
            if (!planIsRunnings.length) return;
            var planIsRunning = planIsRunnings
                .filter((a) => a.ID == id)
                ?.shift();
            // console.log("planIsRunning: ", planIsRunning, 'id:', id);

            if (planIsRunning) {
                const quantityPlan = planIsRunning.Quantity ?? 1;
                const shift = await getCurrentShift();
                // console.log("shift: ", shift);

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
                const productionLogs = await getProductionLog(machineId, planIsRunning.ID);
                const runtimes = await getRuntimeHistory(machineId, 1, planIsRunning.ID);

                io.emit(`plan-${planIsRunning.ID}`, {
                    quantity: planIsRunning.Quantity_Production,
                    ng: planIsRunning.Quantity_Error,
                });
                const bom = await masterBom()
                    .where("Product_BOM_ID", "=", planIsRunning.Product_ID)
                    .where("Mold_ID", "=", planIsRunning.Mold_ID)
                    .first();
                // console.log("bom: ", bom);
                const planProductionLogs = productionLogs.filter(
                    (val) =>
                        val.Command_Production_Detail_ID == planIsRunning.ID
                );

                if (planProductionLogs.length) {
                    const lastProductionLog =
                        planProductionLogs[planProductionLogs.length - 1];

                    if (await checkCondition(lastProductionLog, planIsRunning)) {
                        const log = await createProductionLog({
                            quantityPlan,
                            plan:planIsRunning,
                            bom,
                            total: planIsRunning.Cavity_Real * Number(stt),
                            note: "produce 42",
                        });
                        productionLogs.push(log);
                    } else {
                        lastProductionLog.Total =
                            Number(lastProductionLog.Total) +
                            Number(lastProductionLog.Cavity) * Number(stt);
                        await productionLog()
                            .where("ID", "=", lastProductionLog.ID)
                            .update({ Total: lastProductionLog.Total });
                    }
                } else {
                    // console.log("planIsRunning: ", planIsRunning, "id:", id);
                    const log = await createProductionLog({
                        quantityPlan,
                        plan: planIsRunning,
                        bom,
                        total: planIsRunning.Cavity_Real * Number(stt),
                        ng:0,
                        note: "produce 51",
                    });
                    productionLogs.push(log);
                }

                for (const productionLog of productionLogs) {
                    paramsDay.total += Number(productionLog.Total);
                    paramsDay.ng += Number(productionLog.NG);
                    paramsDay.netRuntime +=
                        (((Number(productionLog.Total)-Number(productionLog.NG))/productionLog.Cavity) *
                        (Number(productionLog.Cycletime)));
                    if (productionLog.Master_Shift_ID == shift.ID) {
                        paramsShift.total += Number(productionLog.Total);
                        paramsShift.ng += Number(productionLog.NG);
                        paramsShift.netRuntime +=
                        (((Number(productionLog.Total)-Number(productionLog.NG))/productionLog.Cavity) *
                        (Number(productionLog.Cycletime)));
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

                paramsDay.fullRunTime = getFullRuntime(planIsRunning.Time_Real_Start);
                paramsShift.fullRunTime = getFullRuntime(planIsRunning.Time_Real_Start);

                paramsDay.plannedTime = await getPlannedTime("day")();
                paramsShift.plannedTime = await getPlannedTime("shift")();
                console.log('paramsDay:',paramsDay);
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
                        Note: "produce insert",
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
                        Note: "produce insert",
                        Master_Shift_ID: shift.ID,
                        ...resultOeeShift,
                    });
                }

                io.emit(`machine-${machineId}`, {
                    shift: resultOeeShift,
                    day: resultOeeDay,
                    productionDetail: {
                        shift: {
                            quantity: paramsShift.total,
                            ng: paramsShift.ng,
                        },
                        day: {
                            quantity: paramsDay.total,
                            ng: paramsDay.ng,
                        },
                    },
                });
            }
        } catch (error) {
            produceLogger.error(error.stack);
        }
    },

    async qc({ io, payload }) {
        qcLogger.info(JSON.stringify(payload));

        try {
            const {
                Plan_Id: planId,
                Plastic_missing,
                Bavia,
                Dim,
                Shape_change,
                IBUTSU,
                Orther,
            } = payload;

            const plan = await commandProductionDetail()
                .where("IsDelete", "=", 0)
                .where("ID", "=", planId)
                .first();

            if (plan) {
                const shift = await getCurrentShift();
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
                const machineId = plan.Part_Action;
                const bom = await masterBom()
                    .where("Product_BOM_ID", "=", plan.Product_ID)
                    .where("Mold_ID", "=", plan.Mold_ID)
                    .first();

                const productionLogs = await getProductionLog(machineId, plan.ID);
                const planProductionLogs = productionLogs.filter(
                    (val) => val.Command_Production_Detail_ID == plan.ID
                );
                const runtimes = await getRuntimeHistory(machineId, 1, plan.ID);
                if (Number(Plastic_missing) > 0) {
                    const lastProductionLogByStatus = planProductionLogs.filter(
                        (val) => val.Master_Status_ID == 11
                    );
                    if (lastProductionLogByStatus.length) {
                        const lastProductionLog =
                            lastProductionLogByStatus[
                                lastProductionLogByStatus.length - 1
                            ];

                        if (await checkCondition(lastProductionLog, plan)) {
                            const log = await createProductionLog({
                                plan,
                                bom,
                                total: 0,
                                ng: Plastic_missing,
                                note: "qc 170",
                                status_id: 11,
                            });
                            await productDefectiveLog().insert({
                                Production_Log_ID: log.ID,
                                Master_Shift_ID: shift.ID,
                                Master_Machine_ID: machineId,
                                Quantity: Plastic_missing,
                            });
                            productionLogs.push(log);
                        } else {
                            lastProductionLog.NG =
                                Number(lastProductionLog.NG) +
                                Number(Plastic_missing);
                            await productionLog()
                                .where("ID", "=", lastProductionLog.ID)
                                .update({ NG: lastProductionLog.NG });
                            const productDefective = await productDefectiveLog()
                                .where("Master_Machine_ID", "=", machineId)
                                .where("Master_Shift_ID", "=", shift.ID)
                                .where(
                                    "Production_Log_ID",
                                    "=",
                                    lastProductionLog.ID
                                )
                                .first();
                            if (productDefective) {
                                await productDefectiveLog()
                                    .where("ID", "=", productDefective.ID)
                                    .update({
                                        Quantity:
                                            Number(productDefective.Quantity) +
                                            Number(Plastic_missing),
                                    });
                            } else {
                                await productDefectiveLog().insert({
                                    Production_Log_ID: lastProductionLog.ID,
                                    Master_Shift_ID: shift.ID,
                                    Master_Machine_ID: machineId,
                                    Quantity: Plastic_missing,
                                });
                            }
                        }
                    } else {
                        const log = await createProductionLog({
                            plan,
                            bom,
                            total: 0,
                            ng: Plastic_missing,
                            note: "qc 201",
                            status_id: 11,
                        });
                        await productDefectiveLog().insert({
                            Production_Log_ID: log.ID,
                            Master_Shift_ID: shift.ID,
                            Master_Machine_ID: machineId,
                            Quantity: Plastic_missing,
                        });
                        productionLogs.push(log);
                    }
                }
                if (Number(Bavia) > 0) {
                    const lastProductionLogByStatus = planProductionLogs.filter(
                        (val) => val.Master_Status_ID == 12
                    );
                    if (lastProductionLogByStatus.length) {
                        const lastProductionLog =
                            lastProductionLogByStatus[
                                lastProductionLogByStatus.length - 1
                            ];

                        if (await checkCondition(lastProductionLog, plan)) {
                            const log = await createProductionLog({
                                plan,
                                bom,
                                total: 0,
                                ng: Bavia,
                                note: "qc 170",
                                status_id: 12,
                            });
                            await productDefectiveLog().insert({
                                Production_Log_ID: log.ID,
                                Master_Shift_ID: shift.ID,
                                Master_Machine_ID: machineId,
                                Quantity: Bavia,
                            });
                            productionLogs.push(log);
                        } else {
                            lastProductionLog.NG =
                                Number(lastProductionLog.NG) + Number(Bavia);
                            await productionLog()
                                .where("ID", "=", lastProductionLog.ID)
                                .update({ NG: lastProductionLog.NG });
                            const productDefective = await productDefectiveLog()
                                .where("Master_Machine_ID", "=", machineId)
                                .where("Master_Shift_ID", "=", shift.ID)
                                .where(
                                    "Production_Log_ID",
                                    "=",
                                    lastProductionLog.ID
                                )
                                .first();
                            if (productDefective) {
                                await productDefectiveLog()
                                    .where("ID", "=", productDefective.ID)
                                    .update({
                                        Quantity:
                                            Number(productDefective.Quantity) +
                                            Number(Bavia),
                                    });
                            } else {
                                await productDefectiveLog().insert({
                                    Production_Log_ID: lastProductionLog.ID,
                                    Master_Shift_ID: shift.ID,
                                    Master_Machine_ID: machineId,
                                    Quantity: Bavia,
                                });
                            }
                        }
                    } else {
                        const log = await createProductionLog({
                            plan,
                            bom,
                            total: 0,
                            ng: Bavia,
                            note: "qc 201",
                            status_id: 12,
                        });
                        await productDefectiveLog().insert({
                            Production_Log_ID: log.ID,
                            Master_Shift_ID: shift.ID,
                            Master_Machine_ID: machineId,
                            Quantity: Bavia,
                        });
                        productionLogs.push(log);
                    }
                }
                if (Number(Dim) > 0) {
                    const lastProductionLogByStatus = planProductionLogs.filter(
                        (val) => val.Master_Status_ID == 13
                    );
                    if (lastProductionLogByStatus.length) {
                        const lastProductionLog =
                            lastProductionLogByStatus[
                                lastProductionLogByStatus.length - 1
                            ];

                        if (await checkCondition(lastProductionLog, plan)) {
                            const log = await createProductionLog({
                                plan,
                                bom,
                                total: 0,
                                ng: Dim,
                                note: "qc 170",
                                status_id: 13,
                            });
                            await productDefectiveLog().insert({
                                Production_Log_ID: log.ID,
                                Master_Shift_ID: shift.ID,
                                Master_Machine_ID: machineId,
                                Quantity: Dim,
                            });
                            productionLogs.push(log);
                        } else {
                            lastProductionLog.NG =
                                Number(lastProductionLog.NG) + Number(Dim);
                            await productionLog()
                                .where("ID", "=", lastProductionLog.ID)
                                .update({ NG: lastProductionLog.NG });
                            const productDefective = await productDefectiveLog()
                                .where("Master_Machine_ID", "=", machineId)
                                .where("Master_Shift_ID", "=", shift.ID)
                                .where(
                                    "Production_Log_ID",
                                    "=",
                                    lastProductionLog.ID
                                )
                                .first();
                            if (productDefective) {
                                await productDefectiveLog()
                                    .where("ID", "=", productDefective.ID)
                                    .update({
                                        Quantity:
                                            Number(productDefective.Quantity) +
                                            Number(Dim),
                                    });
                            } else {
                                await productDefectiveLog().insert({
                                    Production_Log_ID: lastProductionLog.ID,
                                    Master_Shift_ID: shift.ID,
                                    Master_Machine_ID: machineId,
                                    Quantity: Dim,
                                });
                            }
                        }
                    } else {
                        const log = await createProductionLog({
                            plan,
                            bom,
                            total: 0,
                            ng: Dim,
                            note: "qc 201",
                            status_id: 13,
                        });
                        await productDefectiveLog().insert({
                            Production_Log_ID: log.ID,
                            Master_Shift_ID: shift.ID,
                            Master_Machine_ID: machineId,
                            Quantity: Dim,
                        });
                        productionLogs.push(log);
                    }
                }
                if (Number(Shape_change) > 0) {
                    const lastProductionLogByStatus = planProductionLogs.filter(
                        (val) => val.Master_Status_ID == 14
                    );
                    if (lastProductionLogByStatus.length) {
                        const lastProductionLog =
                            lastProductionLogByStatus[
                                lastProductionLogByStatus.length - 1
                            ];

                        if (await checkCondition(lastProductionLog, plan)) {
                            const log = await createProductionLog({
                                plan,
                                bom,
                                total: 0,
                                ng: Shape_change,
                                note: "qc 170",
                                status_id: 14,
                            });
                            await productDefectiveLog().insert({
                                Production_Log_ID: log.ID,
                                Master_Shift_ID: shift.ID,
                                Master_Machine_ID: machineId,
                                Quantity: Shape_change,
                            });
                            productionLogs.push(log);
                        } else {
                            lastProductionLog.NG =
                                Number(lastProductionLog.NG) +
                                Number(Shape_change);
                                await productionLog()
                                    .where("ID", "=", lastProductionLog.ID)
                                    .update({ NG: lastProductionLog.NG });
                            const productDefective = await productDefectiveLog()
                                .where("Master_Machine_ID", "=", machineId)
                                .where("Master_Shift_ID", "=", shift.ID)
                                .where(
                                    "Production_Log_ID",
                                    "=",
                                    lastProductionLog.ID
                                )
                                .first();
                            if (productDefective) {
                                await productDefectiveLog()
                                    .where("ID", "=", productDefective.ID)
                                    .update({
                                        Quantity:
                                            Number(productDefective.Quantity) +
                                            Number(Shape_change),
                                    });
                            } else {
                                await productDefectiveLog().insert({
                                    Production_Log_ID: lastProductionLog.ID,
                                    Master_Shift_ID: shift.ID,
                                    Master_Machine_ID: machineId,
                                    Quantity: Shape_change,
                                });
                            }
                        }
                    } else {
                        const log = await createProductionLog({
                            plan,
                            bom,
                            total: 0,
                            ng: Shape_change,
                            note: "qc 201",
                            status_id: 14,
                        });
                        await productDefectiveLog().insert({
                            Production_Log_ID: log.ID,
                            Master_Shift_ID: shift.ID,
                            Master_Machine_ID: machineId,
                            Quantity: Shape_change,
                        });
                        productionLogs.push(log);
                    }
                }
                if (Number(IBUTSU) > 0) {
                    const lastProductionLogByStatus = planProductionLogs.filter(
                        (val) => val.Master_Status_ID == 15
                    );
                    if (lastProductionLogByStatus.length) {
                        const lastProductionLog =
                            lastProductionLogByStatus[
                                lastProductionLogByStatus.length - 1
                            ];

                        if (await checkCondition(lastProductionLog, plan)) {
                            const log = await createProductionLog({
                                plan,
                                bom,
                                total: 0,
                                ng: IBUTSU,
                                note: "qc 170",
                                status_id: 15,
                            });
                            await productDefectiveLog().insert({
                                Production_Log_ID: log.ID,
                                Master_Shift_ID: shift.ID,
                                Master_Machine_ID: machineId,
                                Quantity: IBUTSU,
                            });
                            productionLogs.push(log);
                        } else {
                            lastProductionLog.NG =
                                Number(lastProductionLog.NG) + Number(IBUTSU);
                            await productionLog()
                                        .where("ID", "=", lastProductionLog.ID)
                                        .update({ NG: lastProductionLog.NG });
                            const productDefective = await productDefectiveLog()
                                .where("Master_Machine_ID", "=", machineId)
                                .where("Master_Shift_ID", "=", shift.ID)
                                .where(
                                    "Production_Log_ID",
                                    "=",
                                    lastProductionLog.ID
                                )
                                .first();
                            if (productDefective) {
                                await productDefectiveLog()
                                    .where("ID", "=", productDefective.ID)
                                    .update({
                                        Quantity:
                                            Number(productDefective.Quantity) +
                                            Number(IBUTSU),
                                    });
                            } else {
                                await productDefectiveLog().insert({
                                    Production_Log_ID: lastProductionLog.ID,
                                    Master_Shift_ID: shift.ID,
                                    Master_Machine_ID: machineId,
                                    Quantity: IBUTSU,
                                });
                            }
                        }
                    } else {
                        const log = await createProductionLog({
                            plan,
                            bom,
                            total: 0,
                            ng: IBUTSU,
                            note: "qc 201",
                            status_id: 15,
                        });
                        await productDefectiveLog().insert({
                            Production_Log_ID: log.ID,
                            Master_Shift_ID: shift.ID,
                            Master_Machine_ID: machineId,
                            Quantity: IBUTSU,
                        });
                        productionLogs.push(log);
                    }
                }
                if (Number(Orther) > 0) {
                    const lastProductionLogByStatus = planProductionLogs.filter(
                        (val) => val.Master_Status_ID == 16
                    );
                    if (lastProductionLogByStatus.length) {
                        const lastProductionLog =
                            lastProductionLogByStatus[
                                lastProductionLogByStatus.length - 1
                            ];

                        if (await checkCondition(lastProductionLog, plan)) {
                            const log = await createProductionLog({
                                plan,
                                bom,
                                total: 0,
                                ng: Orther,
                                note: "qc 170",
                                status_id: 16,
                            });
                            await productDefectiveLog().insert({
                                Production_Log_ID: log.ID,
                                Master_Shift_ID: shift.ID,
                                Master_Machine_ID: machineId,
                                Quantity: Orther,
                            });
                            productionLogs.push(log);
                        } else {
                            lastProductionLog.NG =
                                Number(lastProductionLog.NG) + Number(Orther);
                            await productionLog()
                                            .where("ID", "=", lastProductionLog.ID)
                                            .update({ NG: lastProductionLog.NG });
                            const productDefective = await productDefectiveLog()
                                .where("Master_Machine_ID", "=", machineId)
                                .where("Master_Shift_ID", "=", shift.ID)
                                .where(
                                    "Production_Log_ID",
                                    "=",
                                    lastProductionLog.ID
                                )
                                .first();
                            if (productDefective) {
                                await productDefectiveLog()
                                    .where("ID", "=", productDefective.ID)
                                    .update({
                                        Quantity:
                                            Number(productDefective.Quantity) +
                                            Number(Orther),
                                    });
                            } else {
                                await productDefectiveLog().insert({
                                    Production_Log_ID: lastProductionLog.ID,
                                    Master_Shift_ID: shift.ID,
                                    Master_Machine_ID: machineId,
                                    Quantity: Orther,
                                });
                            }
                        }
                    } else {
                        const log = await createProductionLog({
                            plan,
                            bom,
                            total: 0,
                            ng: Orther,
                            note: "qc 201",
                            status_id: 16,
                        });
                        await productDefectiveLog().insert({
                            Production_Log_ID: log.ID,
                            Master_Shift_ID: shift.ID,
                            Master_Machine_ID: machineId,
                            Quantity: Orther,
                        });
                        productionLogs.push(log);
                    }
                }

                // calculator summary
                for (const productionLog of productionLogs) {
                    paramsDay.total += Number(productionLog.Total);
                    paramsDay.ng += Number(productionLog.NG);
                    paramsDay.netRuntime +=
                    ((Number(productionLog.Total)-Number(productionLog.NG)) *
                    (Number(productionLog.Cycletime)/60));
                    if (productionLog.Master_Shift_ID == shift.ID) {
                        paramsShift.total += Number(productionLog.Total);
                        paramsShift.ng += Number(productionLog.NG);
                        paramsShift.netRuntime +=
                        ((Number(productionLog.Total)-Number(productionLog.NG)) *
                        (Number(productionLog.Cycletime)/60));
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

                paramsDay.fullRunTime = getFullRuntime(plan.Time_Real_Start);
                paramsShift.fullRunTime = getFullRuntime(plan.Time_Real_Start);

                paramsDay.plannedTime = await getPlannedTime("day")();
                paramsShift.plannedTime = await getPlannedTime("shift")();

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
                        Note: "qc insert",
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
                        Note: "qc insert",
                        Master_Shift_ID: shift.ID,
                        ...resultOeeShift,
                    });
                }

                io.emit(`plan-${plan.ID}`, {
                    quantity: plan.Quantity_Production,
                    ng: plan.Quantity_Error,
                });

                io.emit(`machine-${plan.Part_Action}`, {
                    shift: resultOeeShift,
                    day: resultOeeDay,
                    productionDetail: {
                        shift: {
                            quantity: paramsShift.total,
                            ng: paramsShift.ng,
                        },
                        day: { quantity: paramsDay.total, ng: paramsDay.ng },
                    },
                });
            }
        } catch (error) {
            qcLogger.error(JSON.stringify(error.stack));
        }
    },

    async stopMachine({io, payload }) {
        stopMachineLogger.info(JSON.stringify(payload));

        try {
            const { machineId, list_stop: stopError } = payload;
            const history = await getRuntimeHistory(machineId, 2);
            // 1. Dừng theo kế hoạch
            // 2. Thay Khuôn
            // 3. Chờ kiểm tra chất lượng
            // 4. Nguyên nhân khác
            if (history.length) {
                const lastHistory = history[history.length - 1];
                if (Number(stopError) === 1) {
                    await runtimeHistory()
                        .where("ID", "=", lastHistory.ID)
                        .update({ Master_Status_ID: 8 }); // stop due to machine
                    return;
                }
                if (Number(stopError) === 2) {
                    await runtimeHistory()
                        .where("ID", "=", lastHistory.ID)
                        .update({ Master_Status_ID: 7 }); // stop due to change mode
                    return;
                }
                if (Number(stopError) === 4) {
                    await runtimeHistory()
                        .where("ID", "=", lastHistory.ID)
                        .update({ Master_Status_ID: 10 }); // stop due to other
                    return;
                }

                if (Number(stopError) === 3) {
                    await runtimeHistory()
                        .where("ID", "=", lastHistory.ID)
                        .update({ Master_Status_ID: 9 }); // stop due to change mode
                    return;
                }
            }

            io.emit(`machine-${machineId}`, {
                machineStatus: 2,
            });
        } catch (error) {
            stopMachineLogger.error(JSON.stringify(error.stack));
        }
    },

    async errorMachine({io, payload }) {
        errorMachineLogger.info(JSON.stringify(payload));

        try {
            const { machineId, list_err: errorMachine } = payload;
            const history = await getRuntimeHistory(machineId, 3);
            if (history.length) {
                const lastHistory = history[history.length - 1];
                let masterStatusID;

                switch (Number(errorMachine)) {
                    case 1:
                        masterStatusID = 1; //cushion
                        break;
                    case 2:
                        masterStatusID = 2; //runner stuck
                        break;
                    case 3:
                        masterStatusID = 3; //double short
                        break;
                    case 4:
                        masterStatusID = 4; //accume miss
                        break;
                    case 5:
                        masterStatusID = 5; //material runout
                        break;
                    case 6:
                        masterStatusID = 6; //others
                        break;
                    default:
                        masterStatusID = 0;
                        break;
                }
                await runtimeHistory()
                    .where("ID", "=", lastHistory.ID)
                    .update({ Master_Status_ID: masterStatusID });
            }

            io.emit(`machine-${machineId}`, {
                machineStatus: 3,
            });
        } catch (error) {
            errorMachineLogger.error(JSON.stringify(error.stack));
        }
    },

    async startPlan({ io, payload }) {
        startPlanLogger.info(JSON.stringify(payload));

        try {
            const { machineId } = payload;
            const planIsRunning = await commandProductionDetail()
                .where("IsDelete", "=", 0)
                .where("Part_Action", "=", machineId)
                .where("Status", "=", 1)
                .get();

            if (planIsRunning.length) {
                for (const plan of planIsRunning) {
                    const mold = masterMold()
                        .where("ID", "=", plan.Mold_ID)
                        .first();
                    const product = masterProduct()
                        .where("ID", "=", plan.Product_ID)
                        .first();
                    const bom = masterBom()
                        .where("Product_BOM_ID", "=", plan.Product_ID)
                        .where("Mold_ID", "=", plan.Mold_ID)
                        .first();

                    plan.master_product = await product;
                    plan.master_bom = await bom;
                    plan.master_mold = await mold;
                }

                io.emit(`machine-${machineId}`, {
                    plans: planIsRunning,
                });
            }
        } catch (error) {
            startPlanLogger.error(JSON.stringify(error.stack));
        }
    },
    async pausePlan({ io, payload }) {
        startPlanLogger.info(JSON.stringify(payload));

        try {

        } catch (error) {
            startPlanLogger.error(JSON.stringify(error.stack));
        }
    },
    async setupMachine({ io, payload }) {
        setupMachineLogger.info(payload);
    },
};
