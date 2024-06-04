import ResizeObserver from 'resize-observer-polyfill'
import { useEffect, useRef, useContext, useState } from 'react'
import { use, init } from 'echarts/core'
import { LineChart, BarChart } from 'echarts/charts'
import { GridComponent, TitleComponent, LegendComponent, TooltipComponent, DataZoomComponent } from 'echarts/components'
import { SVGRenderer } from 'echarts/renderers'
import useStatistic from '../api/useStatistic'
import DataTable from 'react-data-table-component'
import Select from 'react-select'
import moment from 'moment'
import DateRangePicker from 'react-bootstrap-daterangepicker'
import ReportContext from '../context/ReportContext'
import styles from '../../../scss/oee/statistic-stop.module.scss'
import t from '../../lang'

use([LineChart, BarChart, GridComponent, TitleComponent, LegendComponent, TooltipComponent, DataZoomComponent, SVGRenderer])

const DefectiveProductReport = () => {
    const wrapChart = useRef(null)
    const chart = useRef(null)
    const { getProductDefectiveReport, exportProductDefectiveReport ,cancel } = useStatistic()
    const { machines, loading } = useContext(ReportContext)
    const [selectedMachine, setSelectedMachine] = useState(null)
    const [selectedDate, setSelectedDate] = useState(() => {
        const current = moment().format('YYYY-MM-DD').toString()
        return { startDate: current, endDate: current }
    })
    const [pareto, setPareto] = useState([])
    const [datasheets, setDatasheets] = useState([])
    const [chartDatasheets, setChartDatasheets] = useState([])
    
    const columns = [
        { name: t('Date'),           selector: row => moment(row.Time_Created).format('YYYY-MM-DD').toString()},
        { name: t('Machine'),        selector: row => row.Machine_Name},
        { name: t('Quantity Error'), selector: row => row.Quantity, sortable: true},
    ]

    const option = {
        tooltip: {
            trigger: 'axis',
            axisPointer: {
                type: 'cross'
            }
        },
        legend: {
            data: ['pareto', t('products')]
        },
        xAxis: [{
            type: 'category',
            data: chartDatasheets.map(data => data.name)
        }],
        yAxis: [
            {
                type: 'value',
                name: t('products'),
            },
            {
                type: 'value',
                name: '%',
                min: 0,
                max: 100,
            }
        ],
        grid: {
            top: '10%',
            right: '5%',
            left: '5%',
            bottom: '20%'
        },
        dataZoom: [{
            show: true
        }],
        series: [
            {
                yAxisIndex: 0,
                name: t('products'),
                type: 'bar',
                interval: 50,
                data: chartDatasheets
            },
            {
                yAxisIndex: 1,
                name: 'pareto',
                type: 'line',
                interval: 10,
                data: pareto
            },
        ]
    }

    const handleChangeDate = (e, picker) => {
        const { startDate, endDate } = picker
        setSelectedDate({
            startDate: startDate.format('YYYY-MM-DD').toString(),
            endDate: endDate.format('YYYY-MM-DD').toString(),
        })
    }

    const handleView = () => {
        const machineId = selectedMachine.map(machine => machine.value)
        const { startDate, endDate } = selectedDate
        getProductDefectiveReport(machineId, startDate, endDate)
        .then(res => {
            const { data } = res
            
            if('productDefective' in data) setDatasheets(data.productDefective)

            if('processedProductDefective' in data) {
                const statisticData = Object.entries(data.processedProductDefective).sort((a, b) => b[1] - a[1])
                const sum = statisticData.reduce((total, curr) => total + curr[1], 0)
                const percent = statisticData.map(([key, value]) => Math.floor(value * 10000 / sum) / 100)

                percent.forEach((val, i) => {
                    if(i) percent[i] = percent[i] + percent[i - 1]
                })

                setChartDatasheets(() => statisticData.map(([key, value]) => ({
                    name: key,
                    value
                })))

                setPareto(percent)
            }
        })
        .catch(err => console.log(err))
    }

    const handleExport = () => {
        const machineId = selectedMachine.map(machine => machine.value)
        const { startDate, endDate } = selectedDate
        exportProductDefectiveReport(machineId, startDate, endDate)
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
        loading || setSelectedMachine([machines[0]])
    }, [loading])

    useEffect(() => {
        chart.current && chart.current.setOption(option)
    }, [chartDatasheets])

    useEffect(() => {
        chart.current = init(wrapChart.current)
        chart.current.setOption(option)
        
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
        <div className={styles['statistic-stop']}>
            <div className={styles['title']}>
                {t('Statistics chart of defective product')}
            </div>
            <div className='row mt-2'>
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
                <div className='col-4'>
                    <DateRangePicker
                        initialSettings={{
                            maxDate: moment(),
                            locale: {
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
            <div className={styles['chart']} ref={wrapChart}></div>
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
    )
}

export default DefectiveProductReport