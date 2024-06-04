const { model } = require('./model')

const masterMachine = () => {
    const table = 'Master_Machine'

    return {
        ...model(table)
    }
}

module.exports = masterMachine