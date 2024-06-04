const { app } = require('../../server/express')
const { urlencoded, json } = require('express')

module.exports = () => {
    app.use(urlencoded({
        extended: true
    }))
    app.use(json())
}