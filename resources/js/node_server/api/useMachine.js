import useRequest from './useRequest'

const useMachine = () => {
    const { request, cancel } = useRequest()

    const getMachine = () => request.get('api/machine')

    return {
        getMachine,
        cancel
    }
}

export default useMachine