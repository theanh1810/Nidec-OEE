import ResizeObserver from 'resize-observer-polyfill'
import { useEffect, useRef, useState, memo, useContext } from 'react'
import moment from 'moment'
import { use, init, graphic } from 'echarts/core'
import { CustomChart } from 'echarts/charts'
import { TooltipComponent, DatasetComponent, GridComponent, TitleComponent, DataZoomComponent } from 'echarts/components'
import { CanvasRenderer } from 'echarts/renderers'
import DetailContext from '../context/DetailContext'
import useInitMachine from '../api/useInitMachine'
import styles from '../../../scss/oee/timeline-chart.module.scss'
import t from '../../lang'
import { set } from 'lodash'

use([CustomChart, TooltipComponent, DatasetComponent, GridComponent, TitleComponent, DataZoomComponent, CanvasRenderer])

const TimelineChart = () => {
    const { socket } = useContext(DetailContext)
    const wrapChart = useRef(null)
    const chart = useRef(null)
    const { runtimeHistoryInit, cancel } = useInitMachine()
    const [dataset, setDataset] = useState(() => {
        const d = []
        for(let i = 0; i <= 23; i++) d.push({ source: [] })
        return d
    })
    const getMachineStatus = (machineStatusId) => {
        switch (Number(machineStatusId)) {
            case 0:
                return 1;
            case 7:
            case 8:
            case 9:
            case 10:
                return 2;
            case 1:
            case 2:
            case 3:
            case 4:
            case 5:
            case 6:
                return 3;
            case 17:
                return 4;
            default:
                return null;
        }
    };
    const getColor = machineStatus => {
        switch(Number(machineStatus)) {
            case 1:
                return '#20A551'
            case 2:
                return '#FCB73F'
            case 3:
                return '#FB0D1B'
            // case 4:
            //     return '#787878'
            default:
                return '#787878'
        }
    }

    const getName = machineStatus => {
        switch(Number(machineStatus)) {
            case 1:
                return t('run')
            case 2:
                return t('stop not error')
            case 3:
                return t('stop due to error')
                case 4:
                    return t('Disconnected')
                default:
                    return t('Disconnected')
        }
    }

    const renderItem = (params, api) => {
        let categoryIndex = api.value(0)
        let start = api.coord([api.value(1), categoryIndex])
        let end = api.coord([api.value(2), categoryIndex])
        let height = api.size([0, 1])[1] * 0.8
        let rectShape = graphic.clipRectByRect(
            {
                x: start[0],
                y: start[1] - height / 2,
                width: end[0] - start[0],
                height: height
            },
            {
                x: params.coordSys.x,
                y: params.coordSys.y,
                width: params.coordSys.width,
                height: params.coordSys.height
            }
        )
        return ( rectShape && {
            type: 'rect',
            transition: ['shape'],
            shape: rectShape,
            style: {
                fill: api.value(4)
            }
        })
    }

    const tooltip = params => {
        const marker = `<span style="display:inline-block;margin-right:4px;border-radius:10px;width:10px;height:10px;background-color:${params.value[4]};"></span>`
        const start = moment(Number(params.value[0]), 'hh').add(params.value[1], 'm').format('HH:mm:ss').toString()
        const end = moment(Number(params.value[0]), 'hh').add(params.value[2], 'm').format('HH:mm:ss').toString()
        const duration = moment(0, 'hh').add(params.value[2]-params.value[1], 'm').format('HH:mm:ss').toString()
        return `<div>${marker}${params.value[3]}</div>
                <div>${'Start'}: ${start}</div>
                <div>${'End'}: ${end}</div>
                <div>${'Duration'}: ${duration}</div>`
    }

    const series = () => {
        const s = []
        for(let i = 0; i <= 23; i++) s.push({
            datasetIndex: i,
            type: 'custom',
            renderItem,
            itemStyle: {
                opacity: 0.8,
            },
            encode: {
                y: 0,
                x: [1, 2],
            }
        })
        return s
    }

    const option = {
        tooltip: {
            formatter: tooltip,
            borderColor: 'transparent',
            backgroundColor: 'rgba(255, 255, 255, 0.95)',
            textStyle: {
                fontSize: 12
            }
        },
        dataZoom: [
            {
                type: 'slider',
                filterMode: 'weakFilter',
                showDataShadow: true,
                bottom: 10,
                labelFormatter: '',
                realtime: false
            },
            {
                type: 'inside',
                filterMode: 'weakFilter',
                zoomLock: true
            }
        ],
        grid: {
            left: 60,
            right: 30,
            bottom: 90,
            top: 10,
            width: 'auto',
            height: 'auto'
        },
        xAxis: {
            name: t('minute'),
            nameLocation: 'center',
            nameTextStyle: {
                fontSize: 13,
                fontWeight: 'bold',
                fontFamily: 'monospace'
            },
            nameGap: 30,
            min: 0,
            max: 60,
            axisLabel: {
                formatter: val => val < 60 ? moment('0', 'hh').add(val, 'm').format('mm:ss').toString() : ''
            },
            axisLine: {
                show: true
            },
            splitLine: {
                lineStyle: {
                    type: [5, 5],
                    dashOffset: 5,
                    color: '#afafaf',
                }
            }
        },
        yAxis: {
            name: t('hour'),
            nameLocation: 'center',
            nameTextStyle: {
                fontSize: 13,
                fontWeight: 'bold',
                fontFamily: 'monospace'
            },
            nameGap: 30,
            data: ['23', '22', '21', '20', '19', '18', '17', '16', '15', '14', '13', '12', '11', '10', '09', '08', '07', '06', '05', '04', '03', '02', '01', '00'],
        },
        series: series(),
        dataset
    }

    const updateDataset = ({ isCreated, status, color, index, start, end }) => {
        if(isCreated) {
            setDataset(prevDataset => {
                prevDataset[Number(index)].source.push([ index, start, end, status, color ])
                return [...prevDataset]
            })
        } else {
            setDataset(prevDataset => {
                prevDataset[Number(index)].source.pop()
                prevDataset[Number(index)].source.push([ index, start, end, status, color ])
                return [...prevDataset]
            })
        }
    }

    const processData = ({ data, isCreated }) => {
        const timeCreated = moment(data.Time_Created)
        const timeUpdated = moment(data.Time_Updated)
        const machineStatus = getMachineStatus(data.Master_Status_ID);
        const status = data?.MasterStatus?.Name ? data?.MasterStatus?.Name : getName(machineStatus)
        const color  = getColor(machineStatus)
        if(timeUpdated.date() > timeCreated.date()) {
            const currentDate = moment().date()
            let index, start, end

            if(timeCreated.date() == currentDate) {
                index = '23'
                start = timeCreated.diff(moment(data.Time_Created).startOf('hour'), 'minutes', true)
                end   = 60
            } else {
                index = '00'
                start = 0
                end   = timeUpdated.diff(moment(data.Time_Updated).startOf('hour'), 'minutes', true)
            }

            updateDataset({ isCreated, start, end, index, status, color })
        }

        if(timeUpdated.date() == timeCreated.date()) {
            const timeUpdatedHour = timeUpdated.hour()
            const timeCreateHour  = timeCreated.hour()
            let index, start, end

            if(timeUpdatedHour > timeCreateHour) {
                for(let i = timeCreateHour; i <= timeUpdatedHour; i++) {
                    index = String(i).padStart(2, 0)

                    if(i == timeCreateHour) {
                        start = timeCreated.diff(moment(data.Time_Created).startOf('hour'), 'minutes', true)
                        end = 60
                    }

                    if(i == timeUpdatedHour) {
                        start = 0
                        end = timeUpdated.diff(moment().startOf('day').add(i, 'hours'), 'minutes', true)
                    }

                    if(i > timeCreateHour && i < timeUpdatedHour) {
                        start = 0
                        end = 60
                    }

                    updateDataset({ isCreated, start, end, index, status, color })
                }
            }
            if(timeUpdatedHour == timeCreateHour) {
                index = String(timeCreateHour).padStart(2, 0)
                start = timeCreated.diff(moment(data.Time_Created).startOf('hour'), 'minutes', true)
                end   = timeUpdated.diff(moment(data.Time_Created).startOf('hour'), 'minutes', true)

                updateDataset({ isCreated, start, end, index, status, color })
            }
        }
    }

    const handleSocket = function(msg) {
        if('timeline' in msg) {
            processData(msg.timeline)
        }
    }

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
        const event = `machine-${window.machineId}`
        socket.on(event, handleSocket)

        return () => socket.off(event, handleSocket)
    }, [])

    useEffect(() => {
        chart.current && chart.current.setOption(option)
    }, [dataset])

    useEffect(() => {
        runtimeHistoryInit(window.machineId)
        .then(res => {
            const { status, data } = res.data
            if(status) {
                data.forEach(value => processData({ data: value, isCreated: true }))
            }

        })
        .catch(err => console.log(err))

        return () => cancel()
    }, [])

    return (
        <div className={styles['timeline-chart']}>
            <div className={styles['title']}>{t('Active timeline chart')}</div>
            <div className={styles['chart']} ref={wrapChart}></div>
        </div>
    )
}

export default memo(TimelineChart)
