const { io } = require('../../server/io')
const productionController = require('../controllers/socket/productionController')

const queue = () => {
    const queueList = {}

    const loop = async (key) => {
        if(queueList[key].length) {
            await productionController.produce({ io, payload: queueList[key][0] })
            queueList[key].shift()
            // console.log(`queue: ${moment().format('YYYY-MM-DD HH:mm:ss')}- ${key}`)
            loop(key)
        } else {
            delete queueList[key]
        }
    }

    const push = (key, payload) => {
        if(key in queueList) {
            queueList[key].push(payload)
        } else {
            queueList[key] = [payload]
            loop(key)
        }
    }

    return {
        push
    }
}

module.exports = queue()
