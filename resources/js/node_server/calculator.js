import { createRoot } from 'react-dom/client'
import { useEffect, useState } from 'react'
import { Row, Col, Card, Select, DatePicker, Space, Button, notification, Table } from 'antd'
import useMachine from './api/useMachine'
import useShift from './api/useShift'
import useProductionLog from './api/useProductionLog'
import useRuntime from './api/useRuntime'
import moment from 'moment'
import 'antd/dist/antd.min.css'
import styles from '../../scss/node_server/calculator.module.scss'

const { Option } = Select
const { RangePicker } = DatePicker

const ToolBar = ({ onSubmit }) => {
    const { getMachine, cancel: cancelMachine } = useMachine()
    const { getShift, cancel: cancelShift } = useShift()
    const [machines, setMachines] = useState([])
    const [selectedMachine, setSelectedMachine] = useState(null)
    const [selectedDate, setSelectedDate] = useState([])
    const [shifts, setShifts] = useState([])
    const [selectedShift, setSelectedShift] = useState(null)

    const validate = () => {
        if(!selectedMachine) {
            notification.warning({ message: 'machine does not empty'})
            return false
        }
        if(selectedShift === null) {
            notification.warning({ message: 'shift does not empty'})
            return false
        }
        if(!selectedDate.length) {
            notification.warning({ message: 'date does not empty'})
            return false
        }
        return true
    }

    const handleSubmit = () => {
        if(validate()) onSubmit(selectedMachine, selectedShift, selectedDate.map(date => date.format('YYYY-MM-DD').toString()))
    }

    const initMachine = () => {
        getMachine()
        .then(res => setMachines(res.data))
        .catch(error => console.log(error))
    }

    const initShift = () => {
        getShift()
        .then(res => setShifts(res.data))
        .catch(error => console.log(error))
    }

    useEffect(() => {
        initMachine()
        initShift()

        return () => {
            cancelMachine()
            cancelShift()
        }
    }, [])

    useEffect(() => {
        notification.config({
            placement: 'bottomRight',
            duration: 2
        })
    }, [])
    
    return (
        <Space>
            <Select
                showSearch
                style={{ width: 200 }}
                placeholder="Select machine"
                optionFilterProp="children"
                filterOption={(input, option) => option.children.toLowerCase().includes(input.toLowerCase())}
                value={selectedMachine}
                onChange={setSelectedMachine}
            >
                {machines.map(machine => {
                    return (
                        <Option
                            value={machine.ID}
                            key={`machine-${machine.ID}`}
                        >
                            {machine.Name}
                        </Option>
                    )
                })}
            </Select>
            <Select
                showSearch
                style={{ width: 200 }}
                placeholder="Select shift"
                optionFilterProp="children"
                filterOption={(input, option) => option.children.toLowerCase().includes(input.toLowerCase())}
                value={selectedShift}
                onChange={setSelectedShift}
            >
                <Option
                    value={0}
                    key='shift-0'
                >
                    All shifts
                </Option>
                {shifts.map(shift => {
                    return (
                        <Option
                            value={shift.ID}
                            key={`shift-${shift.ID}`}
                        >
                            {shift.Name}
                        </Option>
                    )
                })}
            </Select>
            <RangePicker
                allowClear
                value={selectedDate}
                onChange={setSelectedDate}
                format={['YYYY-MM-DD', 'YYYY-MM-DD']}
                disabledDate={current => current && current > moment().endOf('day')}
            />
            <Button
                onClick={handleSubmit}
                type='primary'
            >
                Statistic
            </Button>
        </Space>
    )
}

const Calculator = () => {
    const { getProductionLog, cancel: productionLogCancel } = useProductionLog()
    const { getRuntime, cancel: runtimeCancel } = useRuntime()
    const [runtimes, setRuntimes] = useState([])
    const [productionLogs, setProductionLogs] = useState([])

    const runtimeColumns = [
        { title: 'Machine',        dataIndex: ['Master_Machine_ID'],          },
        { title: 'Shift',          dataIndex: ['master_shift', 'Name'],       },
        { title: 'Machine status', dataIndex: ['IsRunning'],                  },
        { title: 'Status',         dataIndex: ['master_status', 'Name'],      },
        { title: 'Status Type',    dataIndex: ['master_status_type', 'Name'], },
        { title: 'Duration',       dataIndex: ['Duration'],                   },
        { title: 'Created at',     dataIndex: ['Time_Created'], render: text => moment(text).format('YYYY-MM-DD HH:mm:ss').toString() },
        { title: 'Updated at',     dataIndex: ['Time_Updated'], render: text => moment(text).format('YYYY-MM-DD HH:mm:ss').toString() },
    ]

    const productionLogColumns = [
        { title: 'Machine',       dataIndex: ['Master_Machine_ID'],    },
        { title: 'Shift',         dataIndex: ['master_shift', 'Name'], },
        { title: 'Total',         dataIndex: ['Total'],                },
        { title: 'NG',            dataIndex: ['NG'],                   },
        { title: 'Quantity plan', dataIndex: ['Quantity_Plan'],        },
        { title: 'Cavity',        dataIndex: ['Cavity'],               },
        { title: 'Cycle time',    dataIndex: ['Cycletime'],            },
        { title: 'Created at',    dataIndex: ['Time_Created'], render: text => moment(text).format('YYYY-MM-DD HH:mm:ss').toString()},
        { title: 'Updated at',    dataIndex: ['Time_Updated'], render: text => moment(text).format('YYYY-MM-DD HH:mm:ss').toString()},
    ]

    const handleSubmit = (selectedMachine, selectedShift, selectedDate) => {
        getProductionLog(selectedMachine, selectedShift, selectedDate)
        .then(res => {
            setProductionLogs(() => res.data.map(value => {
                value.key = value.ID
                return value
            }))
        })
        .catch(error => console.log(error))

        getRuntime(selectedMachine, selectedShift, selectedDate)
        .then(res => {
            setRuntimes(() => res.data.map(value => {
                value.key = value.ID
                return value
            }))
        })
        .catch(error => console.log(error))
    }
    
    useEffect(() => {
        return () => {
            productionLogCancel()
            runtimeCancel()
        }
    }, [])

    return (
        <div className={styles['container']}>
            <Row gutter={[10, 10]}>
                <Col span={24}>
                <Card
                    size="small"
                    title={<ToolBar onSubmit={handleSubmit} />}
                >
                    <Table
                        // loading={loading}
                        columns={runtimeColumns}
                        dataSource={runtimes}
                        size='small'
                        pagination={{
                            pageSize: 5
                        }}
                    />
                    <Table
                        // loading={loading}
                        columns={productionLogColumns}
                        dataSource={productionLogs}
                        size='small'
                        pagination={{
                            pageSize: 5
                        }}
                    />
                </Card>
                </Col>
            </Row>
        </div>
    )
}

const root = createRoot(document.getElementById('app'))
root.render(<Calculator />)