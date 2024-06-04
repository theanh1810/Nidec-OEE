const { model } = require('./model')

const masterBom = () => {
    const table = 'Master_Bom'

    return {
        ...model(table)
    }
}

module.exports = masterBom