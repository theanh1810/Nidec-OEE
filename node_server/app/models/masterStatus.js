const { model } = require('./model')

function masterStatus() {
    const table = 'Master_Status'

    return {
        ...model(table)
    }
}

module.exports = masterStatus