const { model } = require('./model')

function masterStatusType() {
    const table = 'Master_Status_Type'

    return {
        ...model(table)
    }
}

module.exports = masterStatusType