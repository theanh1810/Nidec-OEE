import ResizeObserver from 'resize-observer-polyfill'
import { useContext, useState, useRef, useEffect } from 'react'
import { use, init } from 'echarts/core'
import { LineChart } from 'echarts/charts'
import { TooltipComponent, LegendComponent, DataZoomComponent, GridComponent } from 'echarts/components'
import { CanvasRenderer } from 'echarts/renderers'
import useStatistic from '../api/useStatistic'
import styles from '../../../scss/oee/oee-report-by-day.module.scss'
import t from '../../lang'
import Select from 'react-select'
import moment from 'moment'
import ReportContext from '../context/ReportContext'
import DataTable from 'react-data-table-component'
import DateRangePicker from 'react-bootstrap-daterangepicker'

use([ LineChart, TooltipComponent, LegendComponent, DataZoomComponent, GridComponent, CanvasRenderer ])

const OeeReportByDay = () => {
    const wrapChart = useRef(null)
    const chart = useRef(null)
    const { getOeeReportByDay, exportOeeReportByDay, cancel } = useStatistic()
    const { loading, machines } = useContext(ReportContext)
    const [selectedMachine, setSelectedMachine] = useState(null)
    const [selectedDate, setSelectedDate] = useState(() => {
        const current = moment().format('YYYY-MM-DD').toString()
        return { startDate: current, endDate: current }
    })
    const [category, setCategory] = useState([])
    const [datasheets, setDataSheets] = useState([])
    
    const columns = [
        { name: t('Date'),    selector: row => row.Date, sortable: true},
        { name: 'A (%)',      selector: row => row.A,    sortable: true},
        { name: 'P (%)',      selector: row => row.P,    sortable: true},
        { name: 'Q (%)',      selector: row => row.Q,    sortable: true},
        { name: 'OEE (%)',    selector: row => row.Oee,  sortable: true},
    ]

    const getOption = () => ({
        tooltip: {
            trigger: 'axis'
        },
        xAxis: {
            type: 'category',
            data: category
        },
        yAxis: {
            type: 'value',
            name: '%',
            nameGap: 20,
            min: 0,
        },
        grid: {
            right: '1%',
            left: '5%',
            bottom: '22%',
            top: '13%'
        },
        legend: {
            data: ['OEE', 'A', 'P', 'Q']
        },
        dataZoom: [{
            show: true,
            height: 25
        }],
        series: [
            {
                name: 'OEE',
                type: 'line',
                data: datasheets.map(data => data.Oee)
            },
            {
                name: 'A',
                type: 'line',
                data: datasheets.map(data => data.A)
            },
            {
                name: 'P',
                type: 'line',
                data: datasheets.map(data => data.P)
            },
            {
                name: 'Q',
                type: 'line',
                data: datasheets.map(data => data.Q)
            },
        ]
    })

    const handleChangeDate = (e, picker) => {
        const { startDate, endDate } = picker
        setSelectedDate({
            startDate: startDate.format('YYYY-MM-DD').toString(),
            endDate: endDate.format('YYYY-MM-DD').toString(),
        })
    }

    const handleView = () => {
        const { startDate, endDate } = selectedDate
        getOeeReportByDay(selectedMachine.value, startDate, endDate)
        .then(res => {
            setCategory(Object.keys(res.data))
            setDataSheets(Object.values(res.data))
        })
        .catch(err => console.log(err))
    }

    const handleExport = () => {
        const { startDate, endDate } = selectedDate
        exportOeeReportByDay(selectedMachine.value, startDate, endDate)
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
    
    useEffect(() => {
        chart.current && chart.current.setOption(getOption())
    }, [category, datasheets])

    useEffect(() => {
        loading || setSelectedMachine(machines[0])
    }, [loading])

    useEffect(() => {
        chart.current = init(wrapChart.current)
        chart.current.setOption(getOption())

        const resize = new ResizeObserver(e => {
            chart.current.resize({ width: e[0].borderBoxSize[0].inlineSize })
        })

        chart.current.on('finished', () => {
            resize.observe(wrapChart.current)
        })

        return () => resize.unobserve(wrapChart.current)
    }, [])

    useEffect(() => {
        return () => cancel()
    }, [])

    return (
        <div className={styles['oee-report-by-day']}>
            <div className={styles['title']}>
                {t('Statistical chart of OEE by day')}
            </div>
            <div className='row mt-2'>
                <div className='col-5'>
                    <Select
                        value={selectedMachine}
                        options={machines}
                        onChange={setSelectedMachine}
                        placeholder={t('Select machine')}
                        isLoading={loading}
                        loadingMessage={() => t('Loading data')}
                    />
                </div>
                <div className='col-5'>
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
                <div className='col-2'>
                    <button
                        type="button"
                        className="btn btn-success"
                        onClick={handleView}
                    >
                            {t('View')}
                    </button>
                </div>
            </div>
            <div className='row mt-2'>
                <div className='col'>
                    <div className={styles['chart']} ref={wrapChart}></div>
                </div>
            </div>
            <div className='row mt-2'>
                <div className='col'>
                    <button
                        type="button"
                        className="btn btn-success"
                        onClick={handleExport}
                    >
                        {t('Export excel')}
                    </button>
                </div>
            </div>
            <div className='row mt-2'>
                <div className='col'>
                    <DataTable
                        columns={columns}
                        data={datasheets}
                        pagination
                        paginationRowsPerPageOptions={[10, 20, 40, 50]}
                        paginationComponentOptions={{
                            rowsPerPageText: t('Rows per page'),
                            rangeSeparatorText: '/'
                        }}
                        customStyles={{
                            headCells: {
                                style: {
                                    border: '1px solid #828282',
                                    padding: '.25rem 1rem',
                                    lineHeight: 1,
                                    fontSize: '0.8rem',
                                    backgroundColor: '#E5E5E5'
                                }
                            },
                            headRow: {
                                style: {
                                    minHeight: 'unset'
                                }
                            },
                            cells: {
                                style: {
                                    border: '1px solid #828282',
                                    fontSize: '0.8rem'
                                }
                            },
                        }}
                    />
                </div>
            </div>
        </div>
    )
}

export default OeeReportByDay