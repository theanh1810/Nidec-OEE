import ResizeObserver from "resize-observer-polyfill";
import { useEffect, useRef, useContext, useState } from "react";
import { use, init } from "echarts/core";
import { LineChart, BarChart } from "echarts/charts";
import {
    GridComponent,
    TitleComponent,
    LegendComponent,
    TooltipComponent,
    DataZoomComponent,
} from "echarts/components";
import { SVGRenderer } from "echarts/renderers";
import useStatistic from "../api/useStatistic";
import moment from "moment";
import styles from "../../../scss/oee/statistic-stop.module.scss";
import t from "../../lang";
import VisualizationContext from "../context/VisualizationContext";
import useLine from "../api/useLine";
import { Select, DatePicker } from "antd";
import SettingChart from "./SettingChart";
import ChartContext from "../context/ChartContext";
import { SettingOutlined } from "@ant-design/icons";

const { RangePicker } = DatePicker;
const { MIX_KEY_LOCAL_STORAGE } = process.env;
var localStorageDefectiveKey = `visualization-settingcharts-${MIX_KEY_LOCAL_STORAGE||''}`;

use([
    LineChart,
    BarChart,
    GridComponent,
    TitleComponent,
    LegendComponent,
    TooltipComponent,
    DataZoomComponent,
    SVGRenderer,
]);

const VisualizationDefectiveReport = () => {
    const wrapErrorChart = useRef(null);
    const chart = useRef(null);
    const { lines, line } = useContext(VisualizationContext);
    const { getLines } = useLine();
    const { getProductDefectiveReportByLine, cancel } = useStatistic();
    const [selectedLine, setSelectedLine] = useState(line?.ID);
    const [selectedDate, setSelectedDate] = useState(() => {
        const current = moment().format("YYYY-MM-DD").toString();
        return { startDate: current, endDate: current };
    });
    const [pareto, setPareto] = useState([]);
    const [chartDatasheets, setChartDatasheets] = useState(null);
    const [lineAll, setLineAll] = useState(null);
    const [machines, setMachines] = useState([]);
    const [isSettingChart, setIsSettingChart] = useState(false);

    const showSettingChart = () => setIsSettingChart((prevState) => !prevState);

    const option = {
        tooltip: {
            trigger: "axis",
            axisPointer: {
                type: "cross",
            },
        },
        legend: {
            data: [
                "CHANGE MOLD",
                "BURR",
                "DIM",
                "SHAPE CHANGE",
                "IBUTSU",
                "OTHERS",
                "pareto",
            ],
            bottom: "0",
        },
        xAxis: [
            {
                type: "category",
                data: chartDatasheets?.category,
            },
        ],
        yAxis: [
            {
                type: "value",
            },
            {
                type: "value",
                name: "%",
                min: 0,
                max: 100,
            },
        ],
        grid: {
            top: "10%",
            right: "5%",
            left: "5%",
            bottom: "20%",
        },
        series: [
            // 'mold' => $changeMold,
            //    'burr' => $burr,
            //    'dim' => $dim,
            //    'shape' => $shape,
            //    'IBUTSU' => $IBUTSU,
            //    'others' => $others
            {
                name: "CHANGE MOLD",
                type: "bar",
                stack: "total",
                label: {
                    show: true,
                },
                emphasis: {
                    focus: "series",
                },
                data: chartDatasheets?.mold,
            },
            {
                name: "BURR",
                type: "bar",
                stack: "total",
                label: {
                    show: true,
                },
                emphasis: {
                    focus: "series",
                },
                data: chartDatasheets?.burr,
            },
            {
                name: "DIM",
                type: "bar",
                stack: "total",
                label: {
                    show: true,
                },
                data: chartDatasheets?.dim,
            },
            {
                name: "SHAPE CHANGE",
                type: "bar",
                stack: "total",
                label: {
                    show: true,
                },
                data: chartDatasheets?.shape,
            },
            {
                name: "IBUTSU",
                type: "bar",
                stack: "total",
                label: {
                    show: true,
                },
                data: chartDatasheets?.IBUTSU,
            },
            {
                name: "OTHERS",
                type: "bar",
                stack: "total",
                label: {
                    show: true,
                },
                data: chartDatasheets?.others,
            },
            {
                name: "pareto",
                yAxisIndex: 1,
                type: "line",
                interval: 10,
                data: pareto,
            },
        ],
    };

    const handleChangeDate = async (data) => {
        setSelectedDate({
            startDate: data[0].format("YYYY-MM-DD").toString(),
            endDate: data[1].format("YYYY-MM-DD").toString(),
        });
        await handleView(selectedLine, {
            startDate: data[0].format("YYYY-MM-DD").toString(),
            endDate: data[1].format("YYYY-MM-DD").toString(),
        });
    };

    const handleChangeLine = async (value) => {
        setSelectedLine(value);
        await handleView(value, selectedDate);
    };

    const handleSubmitSettingChart = async (data) => {
        console.log('data:', data.machines.map(({ ID }) => ID));
        console.log('machines:', machines.map(({ ID }) => ID));

        if (
            JSON.stringify(data.machines.map(({ ID }) => ID)) !==
            JSON.stringify(machines.map(({ ID }) => ID))
        ) {
            setMachines(data.machines);
        }

        // save localStorage
        localStorage.setItem(
            localStorageDefectiveKey,
            JSON.stringify({
                machines: data.machines
            })
        );
        await handleView(selectedLine, selectedDate);
    };

    const handleView = async (lineId, dates) => {
        if (!dates) return;
        const { startDate, endDate } = dates;

        const res = await getProductDefectiveReportByLine(
            lineId,
            machines.map((a) => a.ID).join(","),
            startDate,
            endDate
        );
        // console.log('res:',res)
        const { data } = res;

        if ("chartBarData" in data) {
            setChartDatasheets(data.chartBarData);
        }
        if ("processedProductDefective" in data) {
            const statisticData = Object.entries(
                data.processedProductDefective
            ).sort((a, b) => b[1] - a[1]);
            const sum = statisticData.reduce(
                (total, curr) => total + curr[1],
                0
            );
            const percent = statisticData.map(
                ([key, value]) => Math.floor((value * 10000) / sum) / 100
            );

            percent.forEach((val, i) => {
                if (i) percent[i] = percent[i] + percent[i - 1];
            });

            setPareto(percent);
        }
    };

    useEffect(() => {
        chart.current && chart.current.setOption(option);
    }, [chartDatasheets]);

    useEffect(() => {
        chart.current = init(wrapErrorChart.current);
        chart.current.setOption(option);

        const resize = new ResizeObserver((e) => {
            chart.current.resize({ width: e[0].borderBoxSize[0].inlineSize });
        });

        chart.current.on("finished", () => {
            if (wrapErrorChart.current) {
                resize.observe(wrapErrorChart.current);
            }
        });

        return () => {
            if (wrapErrorChart.current) {
                resize.unobserve(wrapErrorChart.current);
            }
        };
    }, []);

    const handleInitData = async () => {
        if (line && line.ID > 0) {
            setSelectedLine(line?.ID);
            setLineAll([
                {
                    value: line?.ID,
                    label: line?.Name,
                },
            ]);
        }
        if (!lines || lines.length == 0) {
            const { success, data: resLine } = await getLines();
            if (success) {
                setLineAll(
                    resLine.data.map(({ ID, Name }) => ({
                        value: ID,
                        label: Name,
                    }))
                );
            }
        }

        const machineLocal = localStorage.getItem(localStorageDefectiveKey);
        const machines = JSON.parse(machineLocal)?.machines;
        if(machines){
            setMachines(machines);
        }
    };
    useEffect(() => {
        handleInitData();
        handleView(selectedLine, selectedDate);
        return () => cancel();
    }, []);

    return (
        <ChartContext.Provider
            value={{
                isSettingChart,
                selectedLine,
                machines,
                handleSubmitSettingChart,
                showSettingChart
            }}
        >
            <div className={styles["statistic-stop"]}>
                <div className="mt-2 row">
                    <div className="col-2">{t("Data analysis")}</div>
                    <div className="col-2">
                        <Select
                            defaultValue={selectedLine}
                            options={lineAll}
                            onChange={handleChangeLine}
                            placeholder={t("Select line")}
                        />
                        <button
                            className="btn btn-light"
                            type="button"
                            onClick={showSettingChart}
                        >
                            <SettingOutlined />
                        </button>

                        <SettingChart />
                    </div>
                    <div className="col-4">
                        <RangePicker
                            defaultValue={[moment(), moment()]}
                            onChange={handleChangeDate}
                        ></RangePicker>
                    </div>
                </div>
                <div className={styles["chart"]} ref={wrapErrorChart}></div>
            </div>
        </ChartContext.Provider>
    );
};

export default VisualizationDefectiveReport;
