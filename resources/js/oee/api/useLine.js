import useRequest from './useRequest'

const useLine = () => {
	const { createGetRequest, cancel } = useRequest('settings/line')

	const getLines = () => createGetRequest({
		endpoint: '/'
	})

	const showLine = (id) => createGetRequest({
		endpoint: '/',
		params: { id }
	})

	return {
		getLines,
		showLine,
		cancel
	}
}

export default useLine
