import axios from 'axios'
import { useState, useEffect } from 'react'
import { message as antdMessage } from 'antd'
import useHandleError from './useHandleError'
import t from '../../lang'
import 'abortcontroller-polyfill'

const useRequest = (prefixPath = '') => {
	const handleError = useHandleError()
	const [controller, setController] = useState(new AbortController())

	const createRequest = () => axios.create({
		baseURL: `${window.location.origin}/api/${prefixPath}`,
		timeout: 15000,
		headers: {
			Accept: 'application/json'
		},
		signal: controller.signal
	})

	const [request, setRequest] = useState(() => createRequest())

	const createGetRequest = ({ endpoint, includeResHeaders = false, ...options }) => {
		return (
			request
				.get(endpoint, options)
				.then(res => {
					const { data } = res
					if (data) {
						const { message } = data
						message && antdMessage.success(t(message))
					}
					return {
						success: true,
						data: includeResHeaders ? res : data
					}
				})
				.catch(err => {
					const data = handleError(err)
					return {
						success: false,
						data
					}
				})
		)
	}

	const createPostRequest = ({ endpoint, data, includeResHeaders = false, ...options }) => {
		return (
			request
				.post(endpoint, data, options)
				.then(res => {
					const { data } = res
					if (data) {
						const { message } = data
						message && antdMessage.success(t(message))
					}
					return {
						success: true,
						data: includeResHeaders ? res : data
					}
				})
				.catch(err => {
					const data = handleError(err)
					return {
						success: false,
						data
					}
				})
		)
	}

	const cancel = () => {
		controller.abort()
		setController(new AbortController())
	}

	useEffect(() => {
		setRequest(() => createRequest())
	}, [controller])

	return {
		request,
		createGetRequest,
		createPostRequest,
		cancel
	}
}

export default useRequest