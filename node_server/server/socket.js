const channels = require('../router/channels')
const { io } = require('./io')
const machineLoopService = require('../app/services/machineLoopService')

global.clients = {}

const start = () => {
    io.on('connection', socket => {
        const { address, headers: { origin, 'user-agent': userAgent } } = socket.handshake
        global.clients[socket.id] = { address, origin, userAgent, id: socket.id, socket }

        socket.on('disconnect', () => {
            console.log('gateway disconnect');
            if(socket.id in global.clients) delete global.clients[socket.id]
        })
        console.log('gateway connection');

        channels(socket, io);
        // console.log('call-status-machine', socket);
        socket.emit('call-status-machine');
    })
}

const getClients = () => global.clients

module.exports = { start, getClients }
