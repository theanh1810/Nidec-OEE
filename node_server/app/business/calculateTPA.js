const moment = require('moment')
const calculateTPA = async (planIsRunning, bom) => {
    let t = 0,
        p = 0,
        a = 0,
        cavity = '';

    for(const plan of planIsRunning) {
        const current = moment()
        const timeStart = moment(plan.Time_Real_Start)
        cavity = `CAV: ${plan.Cavity_Real}/${bom.Cavity}`;
        t += Number(plan.Quantity)
        a += Number(plan.Quantity_Production)
        p += Math.floor((current.diff(timeStart, 'seconds', true) / bom.Cycle_Time))*plan.Cavity_Real
    }


    return { t, p, a, cavity }

}

module.exports = calculateTPA
