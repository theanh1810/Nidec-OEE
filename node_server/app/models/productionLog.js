const { model } = require('./model')

const productionLog = () => {
    const table = 'Production_Log'

    return {
        ...model(table)
    }
}

module.exports = productionLog