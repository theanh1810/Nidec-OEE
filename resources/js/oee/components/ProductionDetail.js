import { useEffect, useState, useContext } from "react";
import { Tab, Tabs } from "react-bootstrap";
import Select from "react-select";
import useMachine from "../api/useMachine";
import ProductionTable from "./ProductionTable";
import DetailContext from "../context/DetailContext";
import t from "../../lang";
import styles from "../../../scss/oee/production-detail.module.scss";

const ProductionDetail = () => {
    const {
        viewOptions,
        selectedViewOption,
        setSelectedViewOption,
        oee,
        a,
        p,
        q,
        plans,
        socket,
        quantity,
        ng,
    } = useContext(DetailContext);
    const { getMachines: get, cancel: machineCancel } = useMachine();
    const [loading, setLoading] = useState(true);
    const [machines, setMachines] = useState([]);
    const [selectedMachine, setSelectedMachine] = useState(null);
    const [bg, setBg] = useState("gray");

    const handleChangeMachine = (e) =>
        (window.location.href = `${window.location.origin}/oee/visualization/detail/${e.value}`);

    const getMachines = (page) => {
        get(page)
            .then((res) => {
                const { data } = res;
                // console.log("res:", res);
                // console.log("data:", data);
                const parseData = data.map((machine) => {
                    const option = { value: machine.ID, label: machine.Name };

                    if (machine.ID == window.machineId)
                        setSelectedMachine(option);

                    return option;
                });

                setMachines((prevState) => prevState.concat(parseData));

                setLoading(false);
            })
            .catch((err) => console.error(err));
    };

    useEffect(() => {
        getMachines();

        return () => machineCancel();
    }, []);

    const handleSocket = function(msg) {
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
        const event = `machine-${window.machineId}`
        socket.on(event, handleSocket)

        return () => socket.off(event, handleSocket)
    }, [])

    return (
        <div className={styles["production-detail"]}>
            <div className="row">
                <div className="col-6">
                    <Select
                        value={selectedMachine}
                        options={machines}
                        onChange={handleChangeMachine}
                        placeholder={t("Select machine")}
                        isLoading={loading}
                        loadingMessage={() => t("Loading data")}
                    />
                </div>
                <div className="col-3">
                    <Select
                        options={viewOptions}
                        value={selectedViewOption}
                        onChange={setSelectedViewOption}
                    />
                </div>
            </div>
            <div className="mt-2 row">
                <div className="col">
                    <Tabs defaultActiveKey={`plan-0`}>
                        {plans.map((plan, index) => {
                            return (
                                <Tab
                                    eventKey={`plan-${index}`}
                                    title={plan.master_product.Name}
                                    key={`#plan-${plan.ID}`}
                                >
                                    <ProductionTable plan={plan} />
                                </Tab>
                            );
                        })}
                    </Tabs>
                </div>
            </div>
            <div className="row">
                <div className="col">
                    <div className={styles["grid-container"]}>
                        <div className={styles["title"]}>{`${t(
                            "Quantity produced of"
                        )} ${selectedViewOption.label}`}</div>
                        <div className={styles["item"]} title={quantity}>
                            {quantity}
                        </div>
                        <div className={styles["title"]}>{`${t(
                            "Quantity NG of"
                        )} ${selectedViewOption.label}`}</div>
                        <div className={styles["item"]} title={ng}>
                            {ng}
                        </div>
                    </div>
                </div>
            </div>
            <div className="mt-2 row">
                <div className="col-3">
                    <div className={styles["card-percent"]} bg={bg}>
                        <div className={styles["title"]}>OEE</div>
                        <div className={styles["percent"]}>{`${oee}%`}</div>
                    </div>
                </div>
                <div className="col-3">
                    <div className={styles["card-percent"]} bg={bg}>
                        <div className={styles["title"]}>
                            {t("Availability")}
                        </div>
                        <div className={styles["percent"]}>{`${a}%`}</div>
                    </div>
                </div>
                <div className="col-3">
                    <div className={styles["card-percent"]} bg={bg}>
                        <div className={styles["title"]}>
                            {t("Performance")}
                        </div>
                        <div className={styles["percent"]}>{`${p}%`}</div>
                    </div>
                </div>
                <div className="col-3">
                    <div className={styles["card-percent"]} bg={bg}>
                        <div className={styles["title"]}>{t("Quality")}</div>
                        <div className={styles["percent"]}>{`${q}%`}</div>
                    </div>
                </div>
            </div>
        </div>
    );
};

export default ProductionDetail;
