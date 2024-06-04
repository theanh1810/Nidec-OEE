import { lazy } from 'react'
const OeeReportByMachine = lazy(() => import('./OeeReportByMachine'))
const OeeReportByDay = lazy(() => import('./OeeReportByDay'))

const OeeReport = () => {
    return (
        <div className="row">
            <div className="col-12">
                <OeeReportByMachine />
            </div>
            {/* <div className="col-6">
                <OeeReportByDay />
            </div> */}
        </div>
    )
}

export default OeeReport
