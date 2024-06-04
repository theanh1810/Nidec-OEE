const { model } = require('./model')

function masterProduct() {
    const table = 'Master_Product'

    return {
        ...model(table)
    }
}

module.exports = masterProduct