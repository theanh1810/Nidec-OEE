import { io } from 'socket.io-client'

const { SOCKET_PORT, SOCKET_HOST } = process.env

let socket = null

const getConnection = () => {
    if(!socket) socket = io(`http://${SOCKET_HOST}:${SOCKET_PORT}`)

    return socket
}

export default getConnection