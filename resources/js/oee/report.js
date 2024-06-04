import { createRoot } from "react-dom/client";
import { useEffect, useState, lazy } from "react";
import useMachine from "./api/useMachine";
import ReportContext from "./context/ReportContext";
import styles from "../../scss/oee/report.module.scss";
import "../../scss/oee/report.scss";
import t from "../lang";
const OeeReport = lazy(() => import("./components/OeeReport"));
const MachineStopReport = lazy(() => import("./components/MachineStopReport"));
const DefectiveProductReport = lazy(() =>
    import("./components/DefectiveProductReport")
);

const Report = () => {
    const { getMachines, cancel } = useMachine();
    const [loading, setLoading] = useState(true);
    const [machines, setMachines] = useState(() => [
        {
            value: 0,
            label: t("All machine"),
        },
    ]);

    const getListMachines = (page) => {
        getMachines(page)
            .then((res) => {
                const parseData = res.data.map((machine) => ({
                    value: machine.ID,
                    label: machine.Name,
                }));

                setMachines((prevState) => prevState.concat(parseData));
                setLoading(false);
            })
            .catch((err) => console.error(err));
    };

    useEffect(() => {
        getListMachines(1);

        return () => cancel();
    }, []);

    return (
        <ReportContext.Provider
            value={{
                loading,
                machines,
            }}
        >
            {/* <Tabs
                defaultActiveKey='oee'
            >
                <Tab
                    eventKey='oee'
                    title={t('OEE')}
                >
                    <OeeReport />
                </Tab>
                <Tab
                    eventKey='stop'
                    title={t('Machine stop logs')}
                >
                    <MachineStopReport />
                </Tab>
                <Tab
                    eventKey='defective'
                    title={t('Defective products')}
                >
                    <DefectiveProductReport />
                </Tab>
            </Tabs> */}
            <div className={styles["report"]}>
                <nav className={styles["tabs"]}>
                    <div className="nav nav-tabs" role="tablist">
                        <a
                            className="nav-link active"
                            data-toggle="tab"
                            href="#oee"
                        >
                            {t("OEE")}
                        </a>
                        <a className="nav-link" data-toggle="tab" href="#stop">
                            {t("Machine stop logs")}
                        </a>
                        <a
                            className="nav-link"
                            data-toggle="tab"
                            href="#product-error"
                        >
                            {t("Defective products")}
                        </a>
                    </div>
                </nav>
                <div className={`${styles["content"]} tab-content mt-2`}>
                    <div className="tab-pane fade show active" id="oee">
                        <OeeReport />
                    </div>
                    <div
                        className='tab-pane fade'
                        id='stop'
                    >
                        <MachineStopReport />
                    </div>
                    <div
                        className='tab-pane fade'
                        id='product-error'
                    >
                        <DefectiveProductReport />
                    </div>
                </div>
            </div>
        </ReportContext.Provider>
    );
};

const root = createRoot(document.getElementById("app"));
root.render(<Report />);
