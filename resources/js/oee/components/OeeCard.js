import styles from '../../../scss/oee/oee-card.module.scss'
import t from '../../lang'
import getColor from '../utils/getColor'

const OeeCard = ({ title, value }) => {
    const bg = getColor(value)
    
    return (
        <div className={styles['oee-card']}>
            <div className={styles['oee-card-header']}>
                {t(title)}
            </div>
            <div className={styles['oee-card-body']}>
                <div className={styles['percent']} bg={bg}>
                    {`${value}%`}
                </div>
            </div>
        </div>
    )
}   

export default OeeCard