const { model } = require('./model')

function productDefectiveLog() {
    const table = 'Product_Defective_Log'

    return {
        ...model(table)
    }
}

module.exports = productDefectiveLog