const { model } = require('./model')

const masterMold = () => {
    const table = 'Master_Mold'

    return {
        ...model(table)
    }
}

module.exports = masterMold