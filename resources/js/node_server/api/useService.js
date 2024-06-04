import useRequest from './useRequest'

const useService = () => {
    const { request, cancel } = useRequest()

    const getMachineServices = (machineId) => request.get('service/machine')

    return {
        getMachineServices,
        cancel
    }
}

export default useService