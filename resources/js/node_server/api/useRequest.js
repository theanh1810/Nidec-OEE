import axios from 'axios'
import { useState } from 'react'

const useRequest = () => {
    const [controller] = useState(new AbortController())

    const [request] = useState(() => axios.create({
        baseURL: `${window.location.origin}`,
        timeout: 10000,
        headers: {},
        signal: controller.signal
    }))

    const cancel = () => controller.abort()
    
    return { request, cancel }
}

export default useRequest