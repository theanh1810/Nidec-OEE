import useRequest from './useRequest'

const useShift = () => {
    const { request, cancel } = useRequest()

    const getShift = () => request.get('api/shift')

    return {
        getShift,
        cancel
    }
}

export default useShift