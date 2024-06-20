import { useContext, useEffect, useState, memo } from 'react'
import VisualizationContext from '../context/VisualizationContext'
import useInitMachine from '../api/useInitMachine'
import styles from '../../../scss/oee/machine-card.module.scss'

const MachineCard = ({ data }) => {
	const { machineCardInit, cancel } = useInitMachine()
	const { socket, mode = 'day' } = useContext(VisualizationContext)
	const [oee, setOee] = useState('--')
	const [product, setProduct] = useState('--')
	const [plan, setPlan] = useState('--')
	const [actual, setActual] = useState('--')
	const [bg, setBg] = useState('gray')

	const gotoDetail = () => window.location.href = `/oee/visualization/detail/${data.ID}`

	const initMachine = () => {
		machineCardInit(data.ID)
			.then(res => {
				const { production, shift } = res.data
				if (shift) {
					setOee(prev => shift.Oee || prev)
				}
				if (production) {
					setActual(production.a)
					console.log('1', production.p);
					setPlan(production.p)
					setProduct(() => production.products.map(product => product.Name).join(' | '))
				}
			})
			.catch(err => console.log(err))
	}

	const handleSocket = function (msg) {
		if (mode in msg) {
			setOee(msg[mode].Oee)
		}
		if ('production' in msg) {
			const { p, a } = msg.production
			console.log('2', p);
			setPlan(p)
			setActual(a)
		}
		if ('plan' in msg) {
			setProduct(() => msg.plan.map(plan => plan.master_product.Name).join(' | '))
		}
		if ('machineStatus' in msg) {
			switch (Number(msg.machineStatus)) {
				case 1:
					setBg('green')
					break
				case 2:
					setBg('orange')
					break
				case 3:
					setBg('red')
					break
				default:
					setBg('gray')
					break
			}
		}
	}

	useEffect(() => {
		const event = `machine-${data.ID}`
		socket.on(event, handleSocket)

		return () => socket.off(event, handleSocket)
	}, [mode])

	useEffect(() => {
		initMachine()

		return () => cancel()
	}, [])

	return (
		<div className={styles['machine-card']}>
			<div
				className={styles['machine-card-header']}
				onClick={gotoDetail}
			>
				{data.Name}
			</div>
			<div className={styles['machine-card-body']}>
				<div
					className={styles['background']}
					bg={bg}
				>
					<div className={styles['percent']} bg={bg}>
						{`${oee} %`}
					</div>
					<div className={styles['info']} bg={bg}>
						{product || 'N/A'}
					</div>
				</div>
			</div>
			<div className={styles['machine-card-footer']}>
				<div className='d-flex'>
					<div className={styles['text']}>P</div>
					<div className={styles['text']}>{plan}</div>
				</div>
				<div className='d-flex'>
					<div className={styles['text']}>A</div>
					<div className={styles['text']}>{actual}</div>
				</div>
			</div>
		</div>
	)
}

export default memo(MachineCard)
