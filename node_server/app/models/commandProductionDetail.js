const { model } = require('./model')

const commandProductionDetail = () => {
    const table = 'Command_Production_Detail'

    return {
        ...model(table)
    }
}

module.exports = commandProductionDetail