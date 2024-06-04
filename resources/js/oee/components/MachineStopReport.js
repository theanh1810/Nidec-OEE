import { lazy, useState, useContext, useEffect } from 'react'
import Select from 'react-select'
import moment from 'moment'
import DateRangePicker from 'react-bootstrap-daterangepicker'
import ReportContext from '../context/ReportContext'
import useStatistic from '../api/useStatistic'
import t from '../../lang'
import styles from '../../../scss/oee/machine-stop-report.module.scss'
const PieChart1 = lazy(() => import('./PieChart1'))
const PieChart2 = lazy(() => import('./PieChart2'))
const PieChart3 = lazy(() => import('./PieChart3'))
const PieChart4 = lazy(() => import('./PieChart4'))
const StatisticStop = lazy(() => import('./StatisticStop'))

const MachineStopReport = () => {
    const { getDataChart1, getDataChart2, getDataChart3, getDataChart4, getStopReport, exportStopReport, cancel } = useStatistic()
    const { machines, loading } = useContext(ReportContext)
    const [selectedMachine, setSelectedMachine] = useState(null)
    const [selectedDate, setSelectedDate] = useState(() => {
        const current = moment().format('YYYY-MM-DD').toString()
        return { startDate: current, endDate: current }
    })
    const [chartData1, setChartData1] = useState({})
    const [chartData2, setChartData2] = useState({})
    const [chartData3, setChartData3] = useState({})
    const [chartData4, setChartData4] = useState({})
    const [stopReport, setStopReport] = useState([])

    const handleView = () => {
        const machineId = selectedMachine.map(machine => machine.value)
        const { startDate, endDate } = selectedDate
        getDataChart1(machineId, startDate, endDate)
        .then(res => setChartData1(res.data))
        .catch(err => console.log(err))

        getDataChart2(machineId, startDate, endDate)
        .then(res => setChartData2(res.data))
        .catch(err => console.log(err))

        getDataChart3(machineId, startDate, endDate)
        .then(res => setChartData3(res.data))
        .catch(err => console.log(err))

        getDataChart4(machineId, startDate, endDate)
        .then(res => setChartData4(res.data))
        .catch(err => console.log(err))

        getStopReport(machineId, startDate, endDate)
        .then(res => {
            const { status, data } = res.data
            if(status) setStopReport(data)
        })
        .catch(err => console.loading(err))

    }

    const handleExport = () => {
        const machineId = selectedMachine.map(machine => machine.value)
        const { startDate, endDate } = selectedDate
        exportStopReport(machineId, startDate, endDate)
        .then(res => {
            const url = window.URL.createObjectURL(new Blob([res.data]))
            const link = document.createElement('a')
            link.href = url
            link.setAttribute('download', res.headers['file-name'])
            document.body.appendChild(link)
            link.click()
        })
        .catch(err => console.log(err))
    }

    const handleChangeDate = (e, picker) => {
        const { startDate, endDate } = picker
        setSelectedDate({
            startDate: startDate.format('YYYY-MM-DD').toString(),
            endDate: endDate.format('YYYY-MM-DD').toString(),
        })
    }

    useEffect(() => {
        loading || setSelectedMachine([machines[0]])
    }, [loading])

    useEffect(() => {
        return () => cancel()
    }, [])

    return (
        <>
            <div className={styles['toolbar']}>
                <div className='row'>
                    <div className='col-4'>
                        <Select
                            isMulti
                            value={selectedMachine}
                            options={machines}
                            onChange={setSelectedMachine}
                            placeholder={t('Select machine')}
                            isLoading={loading}
                            loadingMessage={() => t('Loading data')}
                        />
                    </div>
                    <div className='col-3'>
                        <DateRangePicker
                            initialSettings={{
                                maxDate: moment(),
                                locale: {
                                    format: 'DD/MM/YYYY',
                                    cancelLabel: t('Cancel'),
                                    applyLabel: t('Confirm')
                                }
                            }}
                            onApply={handleChangeDate}
                        >
                            <input
                                type='text'
                                className='form-control'
                            />
                        </DateRangePicker>
                    </div>
                    <div className='col-1'>
                        <button
                            type="button"
                            className="btn btn-success"
                            onClick={handleView}
                        >
                                {t('View')}
                        </button>
                    </div>
                </div>
            </div>
            <div className="row mt-3">
                <div className="col-6">
                    <PieChart1 data={chartData1} />
                </div>
                <div className="col-6">
                    <PieChart2 data={chartData2} />
                </div>
            </div>
            <div className="row mt-3">
                <div className="col-6">
                    <PieChart3 data={chartData3} />
                </div>
                <div className="col-6">
                    <PieChart4 data={chartData4} />
                </div>
            </div>
            <div className='row mt-3'>
                <div className='col-12'>
                    <StatisticStop data={stopReport} onExport={handleExport} />
                </div>
            </div>
        </>
    )
}

export default MachineStopReport
