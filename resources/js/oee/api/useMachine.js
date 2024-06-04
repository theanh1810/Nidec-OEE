import useRequest from './useRequest'

const useMachine = () => {
	const { createGetRequest, cancel } = useRequest('master-machine')

	const getMachines = () => createGetRequest({
		endpoint: '/'
	})

    const getByLine = (id) => createGetRequest({
		endpoint: `/byline/${id}`
	})

	const showMachine = (id) => createGetRequest({
		endpoint: '/',
		params: { id }
	})

	return {
		getMachines,
		showMachine,
		cancel,
        getByLine
	}
}

export default useMachine
