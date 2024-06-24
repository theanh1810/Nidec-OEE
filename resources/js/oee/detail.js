import { createRoot } from 'react-dom/client'
import { lazy, useEffect, useState } from 'react'
import DetailContext from './context/DetailContext'
import getConnection from './socket'
import t from '../lang'
import useInitMachine from './api/useInitMachine'
import useProduction from './api/useProduction'
import { MinusSquareFilled } from '@ant-design/icons'
const ProductionDetail = lazy(() => import('./components/ProductionDetail'))
// const OeeChart = lazy(() => import('./components/OeeChart'))
const TimelineChart = lazy(() => import('./components/TimelineChart'))

const Detail = () => {
    const viewOptions = [
        // { label: t('shift'), value: 'shift' },
        { label: t('day'), value: 'day' }
    ]
    const { machineProductionInit, cancel } = useInitMachine()
    const { getProductionDetail, cancel: productionCancel } = useProduction()
    const socket = getConnection()
    const [selectedViewOption, setSelectedViewOption] = useState(viewOptions[0])
    const [oee, setOee] = useState(0)
    const [a, setA] = useState(0)
    const [p, setP] = useState(0)
    const [q, setQ] = useState(0)
    const [plans, setPlans] = useState([])
    const [quantity, setQuantity] = useState(null)
    const [ng, setNg] = useState(null)

    const handleSocket = (msg) => {
        const { value: mode } = selectedViewOption
        if(mode in msg) {
            const { Oee, A, P, Q } = msg[mode]
            setOee(Oee)
            setA(A)
            setP(P)
            setQ(Q)
        }
        if('plan' in msg) {
            setPlans(msg.plan)
        }
    }

    useEffect(() => {
        const event = `machine-${window.machineId}`
        socket.on(event, handleSocket)

        return () => socket.off(event, handleSocket)
    }, [selectedViewOption])

    useEffect(() => {
        console.log('window.machineId:',window.machineId);
        getProductionDetail(window.machineId, selectedViewOption.value)
        .then(res => {
            const { quantity, ng, oee } = res.data
            setQuantity(quantity)
            setNg(ng)
            setA(oee.A)
            setOee(oee.Oee)
            setQ(oee.Q)
            setP(oee.P)
        })
        .catch(error => console.log(error))
    }, [selectedViewOption])

    useEffect(() => {
        console.log('window.machineId:',window.machineId);
        if(!window.machineId) return;

        machineProductionInit(window.machineId, selectedViewOption.value)
        .then(res => {
            const { plans, shift } = res.data
            if(shift) {
                setA(shift.A)
                setP(shift.P)
                setQ(shift.Q)
                setOee(shift.Oee)
            }
            if(plans) {
                setPlans(plans)
            }
        })
        .catch(err => console.log(err))
    }, [])

    useEffect(() => {
        return () => {
            cancel()
            productionCancel()
        }
    }, [])

    return (
        <DetailContext.Provider value={{
            socket,
            viewOptions,
            selectedViewOption,
            setSelectedViewOption,
            oee,
            a,
            p,
            q,
            plans,
            quantity,
            ng
        }}>
            <div className='row'>
                <div className='col-7'>
                    <ProductionDetail />
                </div>
                <div className='col-5'>
                    {/* <OeeChart /> */}
                </div>
            </div>
            <div className='mt-3 row'>
                <div className='col'>
                    <TimelineChart />
                </div>
            </div>
        </DetailContext.Provider>
    )
}

const root = createRoot(document.getElementById('app'))
root.render(<Detail />)
