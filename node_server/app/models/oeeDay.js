const { model } = require('./model')

function oeeDay() {
    const table = 'Oee_Day'

    return {
        ...model(table)
    }
}

module.exports = oeeDay