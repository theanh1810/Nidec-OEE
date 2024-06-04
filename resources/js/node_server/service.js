import { useState, useEffect } from 'react'
import { Card, Tag, Button } from 'antd'
import { ReloadOutlined } from '@ant-design/icons'
import useService from './api/useService'
import getConnection from './socket'

const Service = () => {
    const socket = getConnection()
    const { getMachineServices, cancel } = useService()
    const [machineServices, setMachineServices] = useState({})
    
    const getColor = machineStatus => {
        switch(machineStatus) {
            case 1:
                return '#20A551'
            case 2:
                return '#FCB73F'
            case 3:
                return '#FB0D1B'
        }
    }

    const renderMachineServices = machineService => {
        return (
            <Tag
                color={getColor(machineService[1])}
                key={`machine-service-${machineService[0]}`}
            >
                {machineService[0]}
            </Tag>
        )
    }

    const get = () => {
        getMachineServices()
        .then(res => {
            setMachineServices(res.data)
        })
        .catch(err => console.log(err))
    }

    useEffect(() => {
        socket.on('machine-service', msg => {
            setMachineServices(msg)
        })
    }, [])

    useEffect(() => {
        get()

        return () => cancelService()
    }, [])

    return (
        <Card
            size="small"
            title="Machine services"
            extra={
                <Button
                    icon={<ReloadOutlined />}
                    onClick={get}
                />
            }
        >
            {Object.entries(machineServices).map(renderMachineServices)}
        </Card>
    )
}

export default Service