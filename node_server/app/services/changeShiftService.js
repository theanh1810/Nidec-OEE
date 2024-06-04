const { CronJob } = require("cron");
const masterShift = require("../models/masterShift");
const masterStatus = require("../models/masterStatus");
const getShift = require("../business/getShift");
const CommandProductionDetail = require("../models/CommandProductionDetail.model");
const CommandProductionRunning = require("../models/CommandProductionRunning");
const { io } = require("../../server/io");
const moment = require("moment");
const masterBom = require("../models/masterBom");
const masterProduct = require("../models/masterProduct");
const masterMold = require("../models/masterMold");

function getDatePlan() {
    const timeNow = moment().format("HHmmss");
    const endTime = "075959";
    if (timeNow < endTime) {
        return moment().subtract(1, "day").format("YYYY-MM-DD");
    } else {
        return moment().format("YYYY-MM-DD");
    }
}

const changeShiftService = () => {
    let shifts = [];
    let currentShift = null;
    let jobs = {};
    let statuses = {};

    const getShifts = async () => {
        const allShift = await masterShift().where("IsDelete", "=", 0).get();
        currentShift = await getShift(allShift);
        return allShift;
    };

    const getStatuses = async () => {
        const statuses = await masterStatus().where("IsDelete", "=", 0).get();
        return statuses;
    };

    const start = async () => {
        shifts = await getShifts();
        statuses = await getStatuses();
        for (const shift of shifts) {
            const start = shift.Start_Time.split(":");
            const end = shift.End_Time.split(":");

            jobs[`${shift.ID}-start`] = new CronJob({
                cronTime: `${start[2]} ${start[1]} ${start[0]} * * *`,
                onTick: async function () {
                    currentShift = shift;
                    // thực hiện clear all data
                    // sent to gateway server
                    const clients = global.clients;
                    Object.keys(clients).forEach(key => {
                        const element = clients[key];
                        element.socket.emit(`clear-all-plan`);
                    });

                    // thực hiện auto start plan
                    const productionRunnings =
                        await CommandProductionRunning.findAll({
                            where: {
                                IsDelete: false,
                                Status: 1,
                            },
                        });
                    productionRunnings.forEach(async (productRunning) => {
                        // finish plan cũ
                        await CommandProductionDetail.update(
                            {
                                Status: 2,
                                Time_Real_End: moment().format(
                                    "YYYY-MM-DD HH:mm:ss"
                                ),
                            },
                            {
                                where: {
                                    ID: productRunning.Command_Detail_ID,
                                },
                            }
                        );

                        // first next plan
                        const planNext = await CommandProductionDetail.findOne({
                            where: {
                                IsDelete: 0,
                                Part_Action: productRunning.Machine_ID,
                                Mold_ID: productRunning.Mold_ID,
                                Status: 0,
                                Date: getDatePlan(),
                            },
                        });
                        if (planNext) {
                            await CommandProductionDetail.update(
                                {
                                    Status: 1,
                                    Time_Real_Start: moment().format(
                                        "YYYY-MM-DD HH:mm:ss"
                                    ),
                                },
                                {
                                    where: {
                                        ID: planNext.ID,
                                    },
                                }
                            );

                            await CommandProductionRunning.update(
                                {
                                    Command_Detail_ID: planNext.ID,
                                },
                                {
                                    where: {
                                        ID: productRunning.ID,
                                    },
                                }
                            );

                            const mold = masterMold()
                                .where("ID", "=", planNext.Mold_ID)
                                .first();
                            const product = masterProduct()
                                .where("ID", "=", planNext.Product_ID)
                                .first();
                            const bom = masterBom()
                                .where("Product_BOM_ID", "=", planNext.Product_ID)
                                .where("Mold_ID", "=", planNext.Mold_ID)
                                .first();

                            planNext.master_product = await product;
                            planNext.master_bom = await bom;
                            planNext.master_mold = await mold;

                            io.emit(`machine-${planNext.Part_Action}`, {
                                plans: [planNext],
                            });

                            // sent to gateway server
                            Object.keys(clients).forEach(key => {
                                const element = clients[key];
                                element.socket.emit(`auto-start-plan`, {
                                    machine_id: planNext.Part_Action,
                                    plans: [planNext],
                                });
                            });
                        }
                    });
                },
                timeZone: "Asia/Ho_Chi_Minh",
            });

            jobs[`${shift.ID}-end`] = new CronJob({
                cronTime: `${end[2]} ${end[1]} ${end[0]} * * *`,
                onTick: function () {
                    currentShift = null;
                    console.log("shift end:", shift);
                },
                timeZone: "Asia/Ho_Chi_Minh",
            });

            jobs[`${shift.ID}-start`].start();
            jobs[`${shift.ID}-end`].start();
        }
    };

    const reset = async () => {
        for (const key in jobs) {
            jobs[key].stop();
            delete jobs[key];
        }

        await start();

        return true;
    };

    const getCurrentShift = async () => {
        if (!currentShift) {
            currentShift = await getShift();
        }

        return currentShift;
    };
    const getAllStatus = async () => {
        if (!statuses) {
            statuses = await getStatuses();
        }

        return statuses;
    };

    return {
        start,
        reset,
        getCurrentShift,
        getAllStatus,
    };
};

module.exports = changeShiftService();
