import ResizeObserver from 'resize-observer-polyfill'
import { useEffect, useRef, useState, useContext } from 'react'
import { init, use } from 'echarts/core'
import { LineChart } from 'echarts/charts'
import { TooltipComponent, GridComponent, TitleComponent, LegendComponent } from 'echarts/components'
import { SVGRenderer } from 'echarts/renderers'
import DetailContext from '../context/DetailContext'
import styles from '../../../scss/oee/oee-chart.module.scss'
import t from '../../lang'
import moment from 'moment'

use([ TooltipComponent, GridComponent, TitleComponent, LegendComponent, SVGRenderer, LineChart])

const OeeChart = () => {
    const dataInit = () => {
        const seconds = 150
        const data = []
        const past = moment().subtract(seconds + 1, 'seconds')
        for(let i = 0; i < seconds; i++) {
            past.add(1, 'seconds')
            data.push({
                name: past.toString(),
                value: [past.format('YYYY/MM/DD HH:mm:ss').toString(), 0]
            })
        }
        return data
    }
    const { oee, a, p, q } = useContext(DetailContext)
    const _oee = useRef(oee)
    const _a = useRef(a)
    const _p = useRef(p)
    const _q = useRef(q)
    const wrapChart = useRef(null)
    const chart = useRef(null)
    const [oeeDatasheet, setOeeDatasheet] = useState(dataInit)
    const [aDatasheet, setADatasheet] = useState(dataInit)
    const [pDatasheet, setPDatasheet] = useState(dataInit)
    const [qDatasheet, setQDatasheet] = useState(dataInit)

    const getOption = () => ({
        tooltip: {
            trigger: 'axis',
            axisPointer: {
                type: 'cross'
            }
        },
        legend: {
            data: ['OEE', 'A', 'P', 'Q'],
        },
        xAxis: {
            type: 'time',
            splitLine: {
                show: false
            },
            splitNumber: 3,
            minInterval: 10000
        },
        yAxis: {
            type: 'value',
            min: 0,
            name: '%',
            splitLine: {
                show: true
            }
        },
        grid: {
            top: '15%',
            right: 0,
            left: '10%',
            bottom: '10%'
        },
        series: [
            {
                name: 'OEE',
                type: 'line',
                smooth: true,
                showSymbol: false,
                data: oeeDatasheet
            },
            {
                name: 'A',
                type: 'line',
                smooth: true,
                showSymbol: false,
                data: aDatasheet
            },
            {
                name: 'P',
                type: 'line',
                smooth: true,
                showSymbol: false,
                data: pDatasheet
            },
            {
                name: 'Q',
                type: 'line',
                smooth: true,
                showSymbol: false,
                data: qDatasheet
            },
        ]
    })
    
    useEffect(() => {
        if(chart.current) {
            chart.current.setOption(getOption())
        }
    }, [oeeDatasheet, aDatasheet, pDatasheet, qDatasheet])
    
    useEffect(() => {
        _oee.current = oee
        _a.current = a
        _p.current = p
        _q.current = q
    }, [oee, a, p, q])

    useEffect(() => {
        const changeData = (prev, val) => {
            prev.shift()
            return [...prev, {
                name: moment().toString(),
                value: [moment().format('YYYY/MM/DD HH:mm:ss').toString(), val]
            }]
        } 
        const interval = setInterval(() => {
            setOeeDatasheet(prev => changeData(prev, _oee.current))
            setADatasheet(prev => changeData(prev, _a.current))
            setPDatasheet(prev => changeData(prev, _p.current))
            setQDatasheet(prev => changeData(prev, _q.current))
        }, 1000)

        return () => clearInterval(interval)
    }, [])

    useEffect(() => {
        chart.current = init(wrapChart.current)
        chart.current.setOption(getOption())
        
        const resize = new ResizeObserver(e => {
            chart.current.resize()
        })

        resize.observe(wrapChart.current)

        return () => resize.unobserve(wrapChart.current)
    }, [])
    
    return (
        <div className={styles['oee-chart']}>
            <div className={styles['container']}>
                <div className={styles['title']}>{t('OEE parameter chart')}</div>
                <div className={styles['chart']} ref={wrapChart}></div>
            </div>
        </div>
    )
}

export default OeeChart