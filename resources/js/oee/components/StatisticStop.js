import ResizeObserver from 'resize-observer-polyfill'
import { useEffect, useRef, useState } from 'react'
import { use, init } from 'echarts/core'
import { LineChart, BarChart } from 'echarts/charts'
import { GridComponent, TitleComponent, LegendComponent, TooltipComponent, DataZoomComponent } from 'echarts/components'
import { SVGRenderer } from 'echarts/renderers'
import DataTable from 'react-data-table-component'
import styles from '../../../scss/oee/statistic-stop.module.scss'
import t from '../../lang'
import moment from 'moment'

use([LineChart, BarChart, GridComponent, TitleComponent, LegendComponent, TooltipComponent, DataZoomComponent, SVGRenderer])

const StatisticStop = ({ data, onExport }) => {
    const wrapChart = useRef(null)
    const chart = useRef(null)
    const [datasheets, setDatasheets] = useState([])
    const [chartDatasheets, setChartDatasheets] = useState([])
    const [pareto, setPareto] = useState([])
    
    const columns = [
        { name: t('Date'),                             selector: row => moment(row.Time_Created).format('YYYY-MM-DD').toString(), sortable: true},
        { name: t('Machine'),                          selector: row => row.Machine_Name,                                         sortable: true},
        { name: `${t('Stop Time')} (${t('minutes')})`, selector: row => Math.floor(row.Duration * 100) / 100,                     sortable: true},
        { name: t('Error Code'),                       selector: row => row.Status_Name},
        { name: t('Error Type'),                       selector: row => t(row.Status_Type)},
    ]

    const option = {
        legend: {
            data: ['pareto', t('machine stop')]
        },
        tooltip: {
            trigger: 'axis',
            axisPointer: {
                type: 'cross'
            }
        },
        xAxis: [{
            type: 'category',
            data: chartDatasheets.map(data => data.name)
        }],
        yAxis: [
            {
                type: 'value',
                name: t('minutes'),
                min: 0,
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
                name: t('machine stop'),
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

    useEffect(() => {
        chart.current && chart.current.setOption(option)
    }, [chartDatasheets])

    useEffect(() => {
        if('runtimeHistory' in data) setDatasheets(data.runtimeHistory)
        if('statisticData' in data) {
            const statisticData = Object.entries(data.statisticData).sort((a, b) => b[1] - a[1])
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
    }, [data])

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
    
    return (
        <div className={styles['statistic-stop']}>
            <div className={styles['title']}>
                {t('Statistics chart of machine time stop')}
            </div>
            <div className={styles['chart']} ref={wrapChart}></div>
            <button
                type="button"
                className="btn btn-success"
                onClick={onExport}
            >
                {t('Export excel')}
            </button>
            <DataTable
                columns={columns}
                data={datasheets}
                pagination
                paginationPerPage={10}
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

export default StatisticStop