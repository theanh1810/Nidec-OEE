const productionLog = require('../../models/productionLog')
const { getCurrentShift } = require('../../services/changeShiftService')

const createProductionLog = async ({ quantityPlan, plan, bom, total, ng, note,status_id = 0 }) => {
    const shift = await getCurrentShift()
    // console.log('createProductionLog plan:', plan)
    // console.log('createProductionLog bom:', bom)
    const log = {
        Command_Production_Detail_ID: plan.ID,
        Master_Shift_ID: shift.ID,
        Master_Machine_ID: plan.Part_Action,
        Total: total || 0,
        NG: ng || 0,
        Runtime: 0,
        Stoptime: 0,
        Quantity_Plan: quantityPlan || 0,
        Cavity: plan.Cavity_Real,
        Cycletime: bom.Cycle_Time,
        Master_Status_ID: status_id,
        Note: note
    }
    // console.log('createProductionLog log:', log)

    const productionLogId = await productionLog().insert(log)

    log.ID = productionLogId
    // console.log('productionLog:', log)

    return log
}

module.exports = createProductionLog
