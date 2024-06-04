const cors = require('cors')
const { io } = require('../../server/io')
const { app, server } = require('../../server/express')

module.exports = () => {
    app.use(cors())
    
    io.attach(server, {
        cors: {
            // origin: process.env.SOCKET_CORS_ORIGIN.split(","),
            origin: "*",
            methods: ["GET", "POST"],
        }
    })
}