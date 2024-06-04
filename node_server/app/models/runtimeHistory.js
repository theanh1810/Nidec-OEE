const { model } = require('./model')

function runtimeHistory() {
    const table = 'Runtime_History'

    return {
        ...model(table)
    }
}

module.exports = runtimeHistory