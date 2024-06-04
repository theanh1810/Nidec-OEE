import useRequest from './useRequest'

const useStatistic = () => {
    const { request, cancel } = useRequest()

    const getOeeReportByMachine = (machineId, startDate, endDate) => request.get('statistic/oee-report-by-machine', {
        params: { machineId, startDate, endDate }
    })

    const getOeeReportByDay = (machineId, startDate, endDate) => request.get('statistic/oee-report-by-day', {
        params: { machineId, startDate, endDate }
    })

    const getProductDefectiveReportByLine = (lineId,machineId, startDate, endDate) => request.get('statistic/product-defective-report-by-line', {
        params: { lineId, machineId, startDate, endDate }
    })
    const getProductDefectiveReport = (machineId, startDate, endDate) => request.get('statistic/product-defective-report', {
        params: { machineId, startDate, endDate }
    })

    const getDataChart1 = (machineId, startDate, endDate) => request.get('statistic/error-and-not-error', {
        params: { machineId, startDate, endDate }
    })

    const getDataChart2 = (machineId, startDate, endDate) => request.get('statistic/machine-error', {
        params: { machineId, startDate, endDate }
    })

    const getDataChart3 = (machineId, startDate, endDate) => request.get('statistic/stop-not-error', {
        params: { machineId, startDate, endDate }
    })

    const getDataChart4 = (machineId, startDate, endDate) => request.get('statistic/stop-quality', {
        params: { machineId, startDate, endDate }
    })

    const getStopReport = (machineId, startDate, endDate) => request.get('statistic/stop-report', {
        params: { machineId, startDate, endDate }
    })

    const exportOeeReportByMachine = (machineId, startDate, endDate) => request.get('statistic/export/oee-report-by-machine', {
        params: { machineId, startDate, endDate },
        responseType: 'blob'
    })

    const exportOeeReportByDay = (machineId, startDate, endDate) => request.get('statistic/export/oee-report-by-day', {
        params: { machineId, startDate, endDate },
        responseType: 'blob'
    })

    const exportStopReport = (machineId, startDate, endDate) => request.get('statistic/export/stop-report', {
        params: { machineId, startDate, endDate },
        responseType: 'blob'
    })

    const exportProductDefectiveReport = (machineId, startDate, endDate) => request.get('statistic/export/product-defective-report', {
        params: { machineId, startDate, endDate },
        responseType: 'blob'
    })

    return {
        getOeeReportByMachine,
        getOeeReportByDay,
        getProductDefectiveReport,
        getProductDefectiveReportByLine,
        getDataChart1,
        getDataChart2,
        getDataChart3,
        getDataChart4,
        getStopReport,
        exportOeeReportByMachine,
        exportOeeReportByDay,
        exportStopReport,
        exportProductDefectiveReport,
        cancel
    }
}

export default useStatistic
