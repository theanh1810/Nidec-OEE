const { CronJob } = require('cron')
const productionController = require('../controllers/express/productionController')
const { io } = require('../../server/io')
const machineLoopService = () => {
    const machines = {}

    const loop = () => {
        Object.entries(machines).map(machine => {
            productionController.runtime({
                machineId: machine[0],
                machineStatus: machine[1]
            })
        })

    }

    const cron = new CronJob({
        cronTime: `*/2 * * * * *`,
        onTick: loop,
        timeZone: 'Asia/Ho_Chi_Minh'
    })

    const start = () => cron.start()

    const stop = () => cron.stop()

    const addOrUpdateMachine = (machineId, machineStatus) => {
        machines[machineId] = machineStatus
        io.emit('machine-service', machines)
    }

    const deleteMachine = (machineId) => {
        if(machineId in machines) delete machines[machineId]
        io.emit('machine-service', machines)
    }

    const deleteAll = () => {
        for(const key in machines) {
            delete machines[key]
        }
        io.emit('machine-service', machines)
    }

    const get = () => machines

    return {
        start,
        stop,
        addOrUpdateMachine,
        deleteMachine,
        deleteAll,
        get,
    }
}

module.exports = machineLoopService()
