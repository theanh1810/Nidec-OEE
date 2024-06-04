import { message, notification } from 'antd'
import t from '../../lang'

import { HTTP_STATUS } from '../configs/app.config'

const {
	BAD_REQUEST,
	UNAUTHORIZED,
	NOT_FOUND,
	METHOD_NOT_ALLOWED,
	TOO_MANY_REQUEST,
	SERVER_ERROR,
} = HTTP_STATUS

const useHandleError = () => {

	const handleError = (error) => {
		const { response, request } = error

		if (response) {
			const { data, status } = response

			switch (status) {
				case BAD_REQUEST:
					message.warning(t(data.message ?? 'bad request'))
					break
				case UNAUTHORIZED:
					notification.info({
						key: 'UNAUTHORIZED',
						message: t('login session expired'),
						description: t('please login again'),
						placement: 'bottomRight'
					})
					break
				case NOT_FOUND:
					message.error(t('url not found'))
					break
				case METHOD_NOT_ALLOWED:
					message.error(t('method not allowed'))
					break
				case TOO_MANY_REQUEST:
					message.error(t('too many request'))
					break
				case SERVER_ERROR:
					notification.error({
						message: t('server error'),
						description: data.message,
						placement: 'bottomRight'
					})
					break
				default:
					message.error(`${t('error')}: ${status}`)
					break
			}

			return data

		} else if (request) {
			const { _hasError, _sent } = request

			// console.log({request})

			if (_hasError) {
				if (_sent) {
					message.error(t('server not respond'))
				} else {
					message.error(t('network error'))
				}
			} else {
				message.error(t('request error unknown'))
				// console.log(1)
				// error any
			}
		} else {
			message.error(t('error unknown'))
			// console.log(error)
			// console.log(2)
			// console.log({error})
			// error any
		}
	}

	return handleError
}

export default useHandleError