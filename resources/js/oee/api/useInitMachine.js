import useRequest from './useRequestNode'

const useInitMachine = () => {
    const { request, cancel } = useRequest()

    const machineCardInit = (machineId) => request.get('api/machine/machine-card-init', {
        params: { machineId }
    })

    const machineProductionInit = (machineId) => request.get('api/machine/machine-production-init', {
        params: { machineId }
    })

    const runtimeHistoryInit = (machineId) => request.get('api/machine/runtime-history-init', {
        params: { machineId }
    })

    return {
        machineCardInit,
        machineProductionInit,
        runtimeHistoryInit,
        cancel
    }
}

export default useInitMachine