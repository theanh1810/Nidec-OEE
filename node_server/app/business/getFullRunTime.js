const moment = require('moment')

const getFullRunTime = (planStartTime) => {
    const current = moment()
    const startOfDay = moment(planStartTime)

    return current.diff(startOfDay, 'seconds')
}

module.exports = getFullRunTime
