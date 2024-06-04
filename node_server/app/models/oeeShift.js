const { model } = require('./model')

function oeeShift() {
    const table = 'Oee_Shift'

    return {
        ...model(table)
    }
}

module.exports = oeeShift