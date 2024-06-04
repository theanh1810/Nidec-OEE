import axios from 'axios'
import { useState } from 'react'
import 'abortcontroller-polyfill'
const { SOCKET_PORT, SOCKET_HOST } = process.env;

const useRequest = () => {
    const [controller] = useState(new AbortController())
    
    const [request] = useState(() => axios.create({
        baseURL: `http://${window.location.hostname}:${SOCKET_PORT}`,
        timeout: 10000,
        headers: {},
        signal: controller.signal
    }))

    const cancel = () => controller.abort()
    
    return { request, cancel }
}

export default useRequest