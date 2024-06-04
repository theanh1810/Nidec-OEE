const { model } = require('./model')

function masterShift() {
    const table = 'Master_Shift'

    return {
        ...model(table)
    }
}

module.exports = masterShift