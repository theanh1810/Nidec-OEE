import useRequest from './useRequest'

const useRuntime = () => {
    const { request, cancel } = useRequest()

    const getRuntime = (selectedMachine, selectedShift, selectedDate) => request.get('api/runtime', {
        params: { selectedMachine, selectedShift, selectedDate }
    })

    return {
        getRuntime,
        cancel
    }
}

export default useRuntime