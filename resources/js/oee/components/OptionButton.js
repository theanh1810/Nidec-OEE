import { useContext } from 'react'
import t from '../../lang'
import VisualizationContext from '../context/VisualizationContext'
import styles from '../../../scss/oee/option-button.module.scss'

import { FontAwesomeIcon } from '@fortawesome/react-fontawesome'
import { faCompress, faExpand, faChevronRight, faCog } from '@fortawesome/free-solid-svg-icons'
import { RetweetOutlined, RightOutlined, SettingOutlined, PauseOutlined } from '@ant-design/icons'

const OptionButton = () => {
	const { isExpand, changeExpandCompress, showSetting, pauseLayout, nextLayout } = useContext(VisualizationContext)

	return (
		<div className={styles['option-button']}>
			<button
				className='btn btn-light'
				type='button'
				onClick={changeExpandCompress}
			>
				<FontAwesomeIcon icon={isExpand ? faCompress : faExpand} />
			</button>
			<button
				className='btn btn-light'
				type='button'
				onClick={nextLayout}
			>
				<RightOutlined />
			</button>
			{/* <button
				className='btn btn-light'
				type='button'
				onClick={changeOption}
			>
				<i className='fa-solid fa-arrow-right-arrow-left'></i>
			</button> */}
			<button
				className='btn btn-light'
				type='button'
				onClick={pauseLayout}
			>
				<PauseOutlined />
			</button>
			<button
				className='btn btn-light'
				type='button'
				onClick={showSetting}
			>
				<SettingOutlined />
			</button>
			{/* <button
				className='btn btn-light'
				type='button'
				onClick={changeExpandCompress}
			>
				<RetweetOutlined />
			</button> */}
		</div>
	)
}

export default OptionButton
