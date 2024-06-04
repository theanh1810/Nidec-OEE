import useRequest from './useRequest'

const useClient = () => {
    const { request, cancel } = useRequest()

    const getClients = () => request.get('client/get-clients')

    return {
        getClients,
        cancel
    }
}

export default useClient