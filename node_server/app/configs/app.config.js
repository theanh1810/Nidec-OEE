const moment = require('moment')

const getStartOfDay = () => moment().startOf('day').add(8, 'hours')

module.exports = {
    getStartOfDay
}