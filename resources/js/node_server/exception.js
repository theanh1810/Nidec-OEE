import { Card, Button } from 'antd'
import { ReloadOutlined } from '@ant-design/icons'

const Exception = () => {
    return (
        <Card
            size="small"
            title="exceptions"
            extra={
                <Button
                    icon={<ReloadOutlined />}
                />
            }
        >
        </Card>
    )
}

export default Exception