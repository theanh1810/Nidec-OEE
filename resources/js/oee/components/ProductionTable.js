import { useContext, useEffect, useState } from 'react'
import styles from '../../../scss/oee/production-table.module.scss'
import t from '../../lang'
import moment from 'moment'
import DetailContext from '../context/DetailContext'

const ProductionTable = ({ plan }) => {
    const { socket } = useContext(DetailContext)
    const [quantity, setQuantity] = useState(plan.Quantity_Production)
    const [ng, setNg] = useState(plan.Quantity_Error)

    useEffect(() => {
        const event = `plan-${plan.ID}`
        const handleSocket = msg => {
            if('quantity' in msg) {
                setQuantity(msg.quantity)
            }
            if('ng' in msg) {
                setNg(msg.ng)
            }
        }
        socket.on(event, handleSocket)

        return () => socket.off(event, handleSocket)
    }, [])

    return (
        <div className={styles['container']}>
            <div className={styles['title']}>{t('Product')}</div>
            <div className={styles['item']} title={plan.master_product.Name}>{plan.master_product.Name}</div>
            <div className={styles['title']}>{t('Mold code')}</div>
            <div className={styles['item']} title={plan.master_mold.Name}>{plan.master_mold.Name}</div>
            <div className={styles['title']}>{t('Actual start time')}</div>
            <div className={styles['item']} title={moment(plan.Time_Real_Start).format('YYYY-MM-DD HH:mm:ss')}>{moment(plan.Time_Real_Start).format('YYYY-MM-DD HH:mm:ss')}</div>
            <div className={styles['title']}>{t('Cycle time') + '(s)'}</div>
            <div className={styles['item']} title={plan.master_bom.Cycle_Time}>{plan.master_bom.Cycle_Time}</div>
            <div className={styles['title']}>{t('Quantity produced')}</div>
            <div className={styles['item']} title={quantity}>{quantity}</div>
            <div className={styles['title']}>{t('Quantity NG')}</div>
            <div className={styles['item']} title={ng}>{ng}</div>
        </div>
    )
}

export default ProductionTable