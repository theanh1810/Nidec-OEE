import moment from "moment";
import ResizeObserver from "resize-observer-polyfill";
import { useRef, useEffect, useContext, useState, memo } from "react";
import { use, init, graphic } from "echarts/core";
import { CustomChart, PieChart } from "echarts/charts";
import {
    DatasetComponent,
    GridComponent,
    TooltipComponent,
} from "echarts/components";
import { CanvasRenderer } from "echarts/renderers";
import useInitMachine from "../api/useInitMachine";
import styles from "../../../scss/oee/visualization-timeline.module.scss";
import VisualizationContext from "../context/VisualizationContext";
import trans from "../../lang";

use([
    CustomChart,
    PieChart,
    DatasetComponent,
    GridComponent,
    TooltipComponent,
    CanvasRenderer,
]);

const getColor = (machineStatus, statusId) => {
    switch (Number(machineStatus)) {
        case 1:
            return "#20A551";
        case 2:
            return "#fdfc43";
        case 3:
            return "#FB0D1B";
        case 4:
            return "#787878";
    }
};
const getMachineStatus = (machineStatusId) => {
    switch (Number(machineStatusId)) {
        case 0:
            return 1;
        case 7:
        case 8:
        case 9:
        case 10:
            return 2;
        case 1:
        case 2:
        case 3:
        case 4:
        case 5:
        case 6:
            return 3;
        case 17:
            return 4;
        default:
            return null;
    }
};
const getName = (machineStatus) => {
    switch (Number(machineStatus)) {
        case 1:
            return trans("run");
        case 2:
            return trans("stop not error");
        case 3:
            return trans("stop due to error");
        case 4:
            return trans("disconnected");
        default:
            return null;
    }
};

const getStatusName = (machineStatus) => {
    switch (Number(machineStatus)) {
        case 1:
            return trans("RUNNING");
        case 2:
            return trans("STOP");
        case 3:
            return trans("ERROR");
        case 4:
            return trans("Disconnected");
        default:
            return trans("Disconnected");
    }
};

const renderItem = (params, api) => {
    let categoryIndex = api.value("index");
    let start = api.coord([api.value("start"), categoryIndex]);
    let end = api.coord([api.value("end"), categoryIndex]);
    let height = api.size([0, 1])[1];
    let rectShape = graphic.clipRectByRect(
        {
            x: start[0],
            y: start[1] - height / 2,
            width: end[0] - start[0],
            height: height,
        },
        {
            x: params.coordSys.x,
            y: params.coordSys.y,
            width: params.coordSys.width,
            height: params.coordSys.height,
        }
    );
    return (
        rectShape && {
            type: "rect",
            transition: ["shape"],
            shape: rectShape,
            style: {
                fill: api.value("color"),
            },
        }
    );
};

const VisualizationTimeline = ({ data, index }) => {
    const { socket } = useContext(VisualizationContext);
    const { machineCardInit, runtimeHistoryInit } = useInitMachine();
    const wrapChart = useRef(null);
    const wrapChartBar = useRef(null);
    const chart = useRef(null);
    const chartBar = useRef(null);
    const [t, setT] = useState(0);
    const [p, setP] = useState(0);
    const [a, setA] = useState(0);
    const [product, setProduct] = useState(null);
    const [cavity, setCavity] = useState(null);
    const [datasheets, setDatasheets] = useState([]);
    const [pieDatasheets, setPieDatasheets] = useState([]);
    const [bg, setBg] = useState("");
    const [messageStatus, setMessageStatus] = useState("");

    const initMachine = () => {
        machineCardInit(data.ID)
            .then((res) => {
                const { production } = res.data;

                if (production) {
                    setA(production.a);
                    setT(production.t);
                    setP(production.p);
                    setCavity(production.cavity);
                    setProduct(() =>
                        production.products
                            .map((product) => product.Name)
                            .join(" | ")
                    );
                }
            })
            .catch((err) => console.log(err));
    };

    const option = {
        xAxis: [
            {
                min: 0,
                max: 1439,
                interval: 180,
                axisLabel: {
                    margin: 2,
                    lineHeight: 10,
                    fontSize: 11,
                    show: index === 0,
                    color: "rgba(255, 255, 255, 1)",
                    fontWeight: "bold",
                    formatter: (value) =>
                        moment()
                            .startOf("day")
                            .add(8, "hours")
                            .add(value, "minutes")
                            .format("HH:mm")
                            .toString(),
                },
                axisLine: {
                    show: true,
                },
                position: "top",
                splitLine: {
                    show: false,
                },
            },
        ],
        yAxis: [
            {
                show: false,
                data: [],
                splitLine: {
                    show: false,
                },
            },
        ],
        grid: {
            show: true,
            top: "10%",
            bottom: 0,
            right: 12,
            left: 12,
            borderColor: "rgba(255, 255, 255, 1)",
        },
        tooltip: {},
        series: [
            {
                name: "timeline",
                datasetIndex: 0,
                type: "custom",
                renderItem: renderItem,
                tooltip: {
                    position: "left",
                    trigger: "item",
                    formatter: ({ data }) => {
                        const start = moment()
                            .startOf("day")
                            .add(8, "hours")
                            .add(data.start, "minutes")
                            .format("HH:mm:ss")
                            .toString();
                        const end = moment()
                            .startOf("day")
                            .add(8, "hours")
                            .add(data.end, "minutes")
                            .format("HH:mm:ss")
                            .toString();
                        const value = moment()
                            .startOf("day")
                            .add(data.value, "minutes")
                            .format("HH:mm:ss")
                            .toString();
                        return `${data.name}<br />${trans(
                            "Start"
                        )}: ${start}<br />${trans("End")}: ${end}<br />${trans(
                            "Duration"
                        )}: ${value}`;
                    },
                },
                itemStyle: {
                    opacity: 0.8,
                },
                encode: {
                    x: [1, 2],
                    y: 0,
                },
            },
        ],
        dataset: [{ source: datasheets }],
    };

    const optionBar = {
        tooltip: {},
        series: [
            {
                name: "pie",
                type: "pie",
                color: pieDatasheets.map((val) => val.color),
                label: {
                    position: "inside",
                    formatter: "{d}%",
                    fontSize: 12,
                },
                tooltip: {
                    trigger: "item",
                    formatter: ({ percent, data, name }) =>
                        `${name}<br />${moment()
                            .startOf("day")
                            .add(data.value, "minutes")
                            .format("HH:mm:ss")
                            .toString()}<br />${percent}%`,
                    position: "left",
                },
                radius: "80%",
                center: ["50%", "55%"],
            },
        ],
        dataset: [{ source: pieDatasheets }],
    };

    const updateDataset = ({
        isCreated,
        name,
        color,
        index,
        start,
        end,
        value,
    }) => {
        if (isCreated) {
            setDatasheets((prevDataset) => {
                prevDataset.push({ index, start, end, name, color, value });
                return [...prevDataset];
            });
        } else {
            setDatasheets((prevDataset) => {
                prevDataset.pop();
                prevDataset.push({ index, start, end, name, color, value });
                return [...prevDataset];
            });
        }
    };

    const processData = ({ data, isCreated }) => {
        const startDay = moment().startOf("day").add(8, "hours");
        const timeCreated = moment(data.Time_Created);
        const timeUpdated = moment(data.Time_Updated);
        const start = timeCreated.diff(startDay, "minutes", true);
        const end = timeUpdated.diff(startDay, "minutes", true);
        const machineStatus = getMachineStatus(data.Master_Status_ID);
        const name = data?.MasterStatus?.Name
            ? data?.MasterStatus?.Name
            : getName(machineStatus);
        const color = getColor(machineStatus);

        updateDataset({
            isCreated,
            index: 0,
            start,
            end,
            name,
            value: end - start,
            color,
        });
    };

    const initRuntimeHistory = () => {
        runtimeHistoryInit(data.ID)
            .then((res) => {
                const { data, status } = res.data;

                if (status) {
                    data.forEach((value) =>
                        processData({ data: value, isCreated: true })
                    );
                }
            })
            .catch((err) => console.log(err));
    };

    const processMachineStatus = ({ data }) => {
        const machineStatus = getMachineStatus(data.Master_Status_ID);
        const name = getStatusName(machineStatus);
        const color = getColor(machineStatus);
        setBg(color);
        setMessageStatus(`${name}: ${data.Duration}`);
    };

    const handleSocket = function (msg) {
        if ("production" in msg) {
            const { t, p, a, cavity } = msg.production;
            setT(t);
            setP(p);
            setA(a);
            setCavity(cavity);
        }
        if ("plan" in msg) {
            setProduct(() =>
                msg.plan.map((plan) => plan.master_product.Name).join(" | ")
            );
        }
        if ("timeline" in msg) {
            processData(msg.timeline);
            processMachineStatus(msg.timeline);
        }
    };

    useEffect(() => {
        const event = `machine-${data.ID}`;
        socket.on(event, handleSocket);

        return () => socket.off(event, handleSocket);
    }, []);

    useEffect(() => {
        chart.current && chart.current.setOption(option);
        chartBar.current && chartBar.current.setOption(optionBar);
    }, [pieDatasheets]);

    useEffect(() => {
        const pieData = datasheets.reduce((data, current) => {
            const name = current.name;
            if (name in data) {
                data[name].value += current.value;
            } else {
                data[name] = {
                    value: current.value,
                    name,
                    color: current.color,
                };
            }

            return data;
        }, {});

        setPieDatasheets(Object.values(pieData));
    }, [datasheets]);

    useEffect(() => {
        chart.current = init(wrapChart.current);
        chart.current.setOption(option);

        chartBar.current = init(wrapChartBar.current);
        chartBar.current.setOption(optionBar);

        const resize = new ResizeObserver((e) => {
            chart.current.resize();
            chartBar.current.resize();
        });

        const wrap = document.getElementById("app");

        chart.current.on("finished", () => {
            resize.observe(wrap);
        });
        chartBar.current.on("finished", () => {
            resize.observe(wrap);
        });

        return () => resize.unobserve(wrap);
    }, []);

    useEffect(() => {
        initMachine();
        initRuntimeHistory();
    }, []);

    return (
        <div className={styles["visualization-timeline"]}>
            <div
                className={styles["flex-container"]}
                style={{ alignItems: "flex-end" }}
            >
                <div
                    className={styles["flex-item1"]}
                    style={{ marginRight: 5 }}
                >
                    <div className={styles["grid-container"]}>
                        <div style={{display: "flex", height: '33.33%'}}>
                            <div
                                className={`${styles["grid-item-1"]} ${styles["border"]} col-lg-8`}
                                title={data.Name}
                            >
                                <div className={`${styles["font-1"]}`}>{data.Name}</div>
                            </div>
                            <div
                                className={`${styles["grid-item-2"]} ${styles["border"]} col-lg-2`}
                            >
                                <div className={`${styles["font-2"]}`}>T</div>
                            </div>
                            <div
                                className={`${styles["grid-item-3"]} ${styles["border"]} col-lg-2`}
                            >
                                <div className={`${styles["font-3"]}`}>{t}</div>
                            </div>
                        </div>
                        <div style={{display: "flex", height: '33.33%'}}>
                            <div
                                className={`${styles["grid-item-1"]} ${styles["border"]} col-lg-8`}
                                title={product}
                            >
                                <div className={`${styles["font-1"]}`}>{product}</div>
                            </div>
                            <div
                                className={`${styles["grid-item-2"]} ${styles["border"]} col-lg-2`}
                            >
                                <div className={`${styles["font-2"]}`}>P</div>
                            </div>
                            <div
                                className={`${styles["grid-item-3"]} ${styles["border"]} col-lg-2`}
                            >
                                <div className={`${styles["font-3"]}`}>{p}</div>
                            </div>
                        </div>
                        <div style={{display: "flex", height: '33.34%'}}>
                            <div
                                className={`${styles["grid-item-1"]} ${styles["border"]} col-lg-8`}
                                title={cavity}
                            >
                                <div className={`${styles["font-1"]}`}>{cavity}</div>
                            </div>
                            <div
                                className={`${styles["grid-item-2"]} ${styles["border"]} col-lg-2`}
                            >
                                <div className={`${styles["font-2"]}`}>A</div>
                            </div>
                            <div
                                className={`${styles["grid-item-3"]} ${styles["border"]} col-lg-2`}
                            >
                                <div className={`${styles["font-3"]}`}>{a}</div>
                            </div>
                        </div>
                    </div>
                </div>
                <div className={styles["flex-item2"]}>
                    <div className={styles["chart-container"]}>
                        <div className={styles["chart"]} ref={wrapChart}></div>
                        <div className={styles["chart-pie-container"]}>
                            <div
                                className={styles["chart-pei"]}
                                ref={wrapChartBar}
                            ></div>
                            <div
                                className={styles["status-block"]}
                                style={{ backgroundColor: bg }}
                            >
                                {messageStatus}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    );
};

export default memo(VisualizationTimeline);
