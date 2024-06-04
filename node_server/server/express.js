const app = require('express')()
const server = require('http').createServer(app)

const start = () => {
    app.get('/', (req, res) => {res.send("Success")});
    server.listen(process.env.PORT || (process.env.PORT_EXPRESS || 3000), () => {
        console.log(`express server start on ${process.env.PORT || (process.env.PORT_EXPRESS || 3000)}`)
    })
}

module.exports = { app, server, start }