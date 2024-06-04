const HTTP_STATUS = Object.freeze({
	BAD_REQUEST: 400,
	UNAUTHORIZED: 401,
	NOT_FOUND: 404,
	METHOD_NOT_ALLOWED: 405,
	TOO_MANY_REQUEST: 429,
	SERVER_ERROR: 500
})

const DATE_TIME_FORMAT = 'YYYY-MM-DD HH:mm:ss'
const TIME_FORMAT = 'HH:mm:ss'
const DATE_FORMAT = 'YYYY-MM-DD'

export {
	HTTP_STATUS,
	DATE_TIME_FORMAT,
	TIME_FORMAT,
	DATE_FORMAT,
}