import useRequest from './useRequestNode'

const useProduction = () => {
    const { request, cancel } = useRequest()

    const getProductionDetail = (machineId, type) => request.get('api/production-log/detail', {
        params: { machineId, type }
    })

    return {
        getProductionDetail,
        cancel
    }
}

export default useProduction