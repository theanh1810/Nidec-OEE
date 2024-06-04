const machineLoopService = require('../../services/machineLoopService')

module.exports = {
    getMachineService(req, res) {
        const service = machineLoopService.get()
        res.json(service)
    }
}