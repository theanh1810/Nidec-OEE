import { createRoot } from 'react-dom/client'
import { Row, Col } from 'antd'
import Service from './service'
import Client from './client'
import Exception from './exception'
import 'antd/dist/antd.min.css'
import styles from '../../scss/node_server/monitor.module.scss'

const Monitor = () => {
    return (
        <div className={styles['container']}>
            <Row gutter={[10, 10]}>
                <Col span={12}>
                    <Service />
                </Col>
                <Col span={12}>
                    <Client />
                </Col>
                <Col span={12}>
                    <Exception />
                </Col>
            </Row>
        </div>
    )
}

const root = createRoot(document.getElementById('app'))
root.render(<Monitor />)