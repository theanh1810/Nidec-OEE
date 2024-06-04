import { useEffect, useState } from 'react'
import { Card, Table, Button } from 'antd'
import { ReloadOutlined } from '@ant-design/icons'
import useClient from './api/useClient'

const Client = () => {
    const { getClients, cancel } = useClient()
    const [clients, setClients] = useState([])
    const [loading, setLoading] = useState(false)

    const columns = [
        { title: 'ID',         ellipsis: true, dataIndex: 'id'},
        { title: 'Address',    ellipsis: true, dataIndex: 'address', render: text => text?.replace('::ffff:', '') },
        { title: 'Origin',     ellipsis: true, dataIndex: 'origin',  render: text => text?.replace('http://', '') }, 
        { title: 'User Agent', ellipsis: true, dataIndex: 'userAgent'},
    ]

    const get = () => {
        setLoading(true)

        getClients()
        .then(res => {
            console.log(res.data)
            setClients(() => Object.values(res.data).map(val => {
                val.key = val.id
                return val
            }))
        })
        .catch(err => console.log(err))
        .finally(() => setLoading(false))
    }

    useEffect(() => {
        get()

        return () => cancel()
    }, [])

    return (
        <Card
            size="small"
            title="clients"
            extra={
                <Button
                    icon={<ReloadOutlined />}
                    onClick={get}
                />
            }
        >
            <Table
                loading={loading}
                columns={columns}
                dataSource={clients}
                size='small'
            />
        </Card>
    )
}

export default Client