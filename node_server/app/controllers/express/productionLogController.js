const productionLog = require("../../models/productionLog");
const masterShift = require("../../models/masterShift");
const moment = require("moment");
const { getStartOfDay } = require("../../configs/app.config");
const { getCurrentShift } = require("../../services/changeShiftService");
const getOee = require("../../business/oee/getOee");
const commandProductionDetail = require("../../models/commandProductionDetail");

module.exports = {
    async index(req, res) {
        const { query } = req;
        const { selectedMachine, selectedShift, selectedDate } = query;
        const startDate = moment(selectedDate[0])
            .add(8, "hour")
            .format("YYYY-MM-DD HH:mm:ss")
            .toString();
        const endDate = moment(selectedDate[1])
            .add(8, "hour")
            .add(1, "day")
            .format("YYYY-MM-DD HH:mm:ss")
            .toString();
        let productionLogs = productionLog()
            .where("Master_Machine_ID", "=", selectedMachine)
            .where("Time_Created", ">=", startDate)
            .where("Time_Updated", "<=", endDate);

        if (Number(selectedShift))
            productionLogs.where("Master_Shift_ID", "=", selectedShift);

        productionLogs = await productionLogs.get();

        if (productionLogs.length) {
            for (const production of productionLogs) {
                const { Master_Shift_ID } = production;
                const shift = await masterShift().find(Master_Shift_ID);
                production.master_shift = shift;
            }
        }

        res.json(productionLogs);
    },

    async detail(req, res) {
        const { query } = req;
        const { machineId, type } = query;
        let quantity = 0,
            ng = 0;
        let productionLogs = productionLog()
            .where("Master_Machine_ID", "=", machineId)
            .where(
                "Time_Created",
                ">=",
                getStartOfDay().format("YYYY-MM-DD HH:mm:ss").toString()
            );
        const oee = await getOee(type, machineId);

        if (type === "shift") {
            const currentShift = await getCurrentShift();
            productionLogs.where("Master_Shift_ID", "=", currentShift.ID);
        }

        productionLogs = await productionLogs.get();

        if (productionLogs.length) {
            for (const production of productionLogs) {
                quantity += Number(production.Total);
                ng += Number(production.NG);
            }
        }

        res.json({ quantity, ng, oee });
    },
};
