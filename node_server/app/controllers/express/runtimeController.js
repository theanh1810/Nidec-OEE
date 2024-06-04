const runtimeHistory = require('../../models/runtimeHistory')
const masterStatus = require('../../models/masterStatus')
const masterStatusType = require('../../models/masterStatusType')
const masterShift = require('../../models/masterShift')
const moment = require('moment')

module.exports = {
    async index(req, res) {
        try {
            const { query } = req
            const { selectedMachine, selectedShift, selectedDate } = query
            const startDate = moment(selectedDate[0]).add(8, 'hour').format('YYYY-MM-DD HH:mm:ss').toString()
            const endDate = moment(selectedDate[1]).add(8, 'hour').add(1, 'day').format('YYYY-MM-DD HH:mm:ss').toString()
            let   runtimes = runtimeHistory().where('Master_Machine_ID', '=', selectedMachine)
                                            .where('Time_Created', '>=', startDate)
                                            .where('Time_Updated', '<=', endDate)
    
            if(Number(selectedShift)) runtimes.where('Master_Shift_ID', '=', selectedShift)
    
            runtimes = await runtimes.get()
    
            if(runtimes.length) {
                for(const runtime of runtimes) {
                    const { Master_Status_ID, Master_Shift_ID } = runtime
                    const status = await masterStatus().find(Master_Status_ID)
                    const shift = await masterShift().find(Master_Shift_ID)
                    runtime.master_status = status
                    runtime.master_shift = shift
                    if(status) {
                        const { Master_Status_Type_ID } = status
                        const type = await masterStatusType().find(Master_Status_Type_ID)
                        runtime.master_status_type = type
                    }
                }
            }
    
            res.json(runtimes)
        } catch(error) {
            console.error(error.stack)
            res.json([])
        }
    },
}