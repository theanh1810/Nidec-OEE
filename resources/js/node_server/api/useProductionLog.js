import useRequest from './useRequest'

const useProductionLog = () => {
    const { request, cancel } = useRequest()

    const getProductionLog = (selectedMachine, selectedShift, selectedDate) => request.get('api/production-log', {
        params: { selectedMachine, selectedShift, selectedDate }
    })

    return {
        getProductionLog,
        cancel
    }
}

export default useProductionLog