const winston = require('winston')
require('winston-daily-rotate-file')
const moment = require('moment')

const createLogger = (name, path) => winston.createLogger({
    transports: [
        new winston.transports.Console({
            format: winston.format.combine(
                winston.format.label({ label: name }),
                winston.format.timestamp({
                    format: () => moment().format('YYYY-MM-DD HH:mm:ss')
                }),
                winston.format.prettyPrint()
            ),
        }),
        new winston.transports.DailyRotateFile({
            filename: `${process.cwd()}${path}/${name}-%DATE%.log`,
            datePattern: 'YYYY-MM-DD',
            maxFiles: '7d',
            maxSize: '1m',
            format: winston.format.combine(
                winston.format.timestamp({
                    format: () => moment().format('YYYY-MM-DD HH:mm:ss')
                }),
                winston.format.json()
            )
        })
    ]
})

const logger = () => {
    const produceLogger = createLogger('produce', '/logs/produce')
    const qcLogger = createLogger('qc', '/logs/qc')
    const timelineLogger = createLogger('runtime', '/logs/timeline')
    const stopMachineLogger = createLogger('stop machine', '/logs/stop-machine')
    const errorMachineLogger = createLogger('error machine', '/logs/error-machine')
    const startPlanLogger = createLogger('start-plan', '/logs/start-plan')
    const setupMachineLogger = createLogger('setup-machine', '/logs/setup-machine')
    
    return {
        produceLogger,
        qcLogger,
        timelineLogger,
        stopMachineLogger,
        errorMachineLogger,
        startPlanLogger,
        setupMachineLogger
    }
}

module.exports = logger()