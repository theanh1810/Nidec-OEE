import ResizeObserver from 'resize-observer-polyfill'
import { useRef, useEffect, useState } from 'react'
import { use, init } from 'echarts/core'
import { PieChart } from 'echarts/charts'
import { GridComponent, LegendComponent, TooltipComponent, TitleComponent } from 'echarts/components'
import { SVGRenderer } from 'echarts/renderers'
import styles from '../../../scss/oee/pie-chart.module.scss'
import t from '../../lang'

use([PieChart, GridComponent, LegendComponent, TooltipComponent, TitleComponent, SVGRenderer])

const PieChart2 = ({ data }) => {
    const wrapChart = useRef(null)
    const chart = useRef(null)
    const [datasheets, setDataSheets] = useState([])
    const [total, setTotal] = useState()
    
    const option = {
        legend: {
            type: 'scroll',
            orient: 'vertical',
            right: 10,
            top: 20,
            bottom: 20,
        },
        tooltip: {
            trigger: 'item',
            formatter: '{b}'
        },
        series: [{
            type: 'pie',
            center: ['40%', '50%'],
            label: {
                show: true,
                formatter: `{c}${t('minutes')} ({d}%)`
            },
            labelLine: {
                smooth: 0.2,
            },
            data: datasheets
        }]
    }

    useEffect(() => {
        chart.current && chart.current.setOption(option)
        setTotal(() => Math.floor(datasheets.reduce((total, current) => {
            return total += current.value
        }, 0) * 100) / 100)
    }, [datasheets])

    useEffect(() => {
        setDataSheets(() => Object.entries(data).map(([key, val]) => ({
            name: key,
            value: Math.round(val * 100) / 100
        })))
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
        <div className={styles['pie-chart']}>
            <div className={styles['title']}>
                {t('Machine error rate chart')}
            </div>
            <div className={styles['chart']} ref={wrapChart}></div>
            <div>{`${t('Total stop time')}: ${total} (${t('minutes')})`}</div>
        </div>
    )
}

export default PieChart2