const commandProductionDetail = require("../../models/commandProductionDetail");
const masterMachine = require("../../models/masterMachine");
const runtimeHistory = require("../../models/runtimeHistory");
const masterBom = require("../../models/masterBom");
const masterMold = require("../../models/masterMold");
const masterProduct = require("../../models/masterProduct");
const moment = require("moment");
const getOee = require("../../business/oee/getOee");
const { Op } = require("sequelize");

module.exports = {
    async getMachine(req, res) {
        res.json(await masterMachine().get());
    },

    async initMachine(req, res) {
        const { query } = req;
        const { machineId } = query;

        const planIsRunning = await commandProductionDetail()
            .where("IsDelete", "=", 0)
            .where("Part_Action", "=", machineId)
            .where("Status", "=", 1)
            .get();
        let p = 0,
            a = 0,
            t = 0,
            cavity = "",
            products = [];

        const oeeShift = await getOee("shift", machineId);

        if (planIsRunning.length) {
            for (const plan of planIsRunning) {
                const current = moment();
                const startTime = moment(plan.Time_Real_Start);
                const bom = await masterBom()
                    .where("Product_BOM_ID", "=", plan.Product_ID)
                    .where("Mold_ID", "=", plan.Mold_ID)
                    .first();
                const product = await masterProduct()
                    .where("ID", "=", plan.Product_ID)
                    .first();
                products.push(product);
                cavity = `CAV: ${plan.Cavity_Real}/${bom.Cavity}`;
                p += Math.floor(
                    (current.diff(startTime, "seconds", true) /
                        bom.Cycle_Time)
                )*plan.Cavity_Real;
                a += Number(plan.Quantity_Production);
                t += Number(plan.Quantity);
            }
        }

        res.json({
            production: {
                p,
                a,
                t,
                cavity: cavity,
                plan: planIsRunning,
                products,
            },
            shift: oeeShift,
        });
    },

    async initMachineProduction(req, res) {
        const { query } = req;
        const { machineId } = query;

        const planIsRunning = await commandProductionDetail()
            .where("IsDelete", "=", 0)
            .where("Part_Action", "=", machineId)
            .where("Status", "=", 1)
            .get();
        const oeeShift = await getOee("shift", machineId);

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
                plan.master_mold = await mold;
                plan.master_bom = await bom;
            }
        }

        res.json({
            plans: planIsRunning,
            shift: oeeShift,
        });
    },

    async initRuntime(req, res) {
        const { query } = req;
        const { machineId } = query;

        const runtime = await runtimeHistory()
        .where("Master_Machine_ID", "=", machineId)
        .where("Time_created", ">=", moment()
        .startOf("day")
        .format("YYYY-MM-DD HH:mm:ss")
        .toString())
        .get();

        res.json({
            status: true,
            message: "successfully",
            data: runtime,
        });
    },
};
