import { createRoot } from "react-dom/client";
import React, { useEffect, useState, useMemo } from "react";
import useMachine from "./api/useMachine";
import getConnection from "./socket";

import "antd/dist/antd.min.css";
import { Row, Col } from "antd";
import MachineCard from "./components/MachineCard";
import OptionButton from "./components/OptionButton";
import HeaderBar from "./components/HeaderBar";
import Setting from "./components/Setting";
import VisualizationTimeline from "./components/VisualizationTimeline";
import VisualizationDefectiveReport from "./components/VisualizationDefectiveReport";
import VisualizationContext from "./context/VisualizationContext";
import useLine from "./api/useLine";

const { MIX_KEY_LOCAL_STORAGE } = process.env;

var localStorageKey = `visualization-settings-${MIX_KEY_LOCAL_STORAGE || ""}`;
const pageNumber = 4;

const MachineCardLayout = ({ machines }) => {
    if (!machines) return;
    const renderMachines = machines.map((machine) => (
        <Col key={`machine-card-${machine.ID}`} span={4}>
            <MachineCard data={machine} />
        </Col>
    ));

    return <Row gutter={[10, 10]}>{renderMachines}</Row>;
};

const MachineChartLayout = ({ machines }) => {
    const renderMachines = machines.map((machine, index) => (
        <Col key={`machine-timeline-${machine.ID}`} span={24}>
            <VisualizationTimeline data={machine} index={index} />
        </Col>
    ));

    return <Row gutter={[10, 10]}>{renderMachines}</Row>;
};
const DefectiveChartLayout = () => {
    return (
        <Row style={{ height: "100%" }} gutter={[10, 10]}>
            <VisualizationDefectiveReport />
        </Row>
    );
};

const Visualization = () => {
    const socket = getConnection();
    const { getByLine } = useMachine();
    const { getLines } = useLine();
    const [machines, setMachines] = useState([]);
    const [intervalLayout, setIntervalLayout] = useState(10);
    const [isExpand, setIsExpand] = useState(false);
    const [layoutIndex, setLayoutIndex] = useState(0);
    const [isSetting, setIsSetting] = useState(false);
    const [isPause, setIsPause] = useState(false);
    const [line, setLine] = useState({});
    const [lines, setLines] = useState([]);
    const [machineTimelines, setMachineTimelines] = useState([]);
    const [pageTimeline, setPageTimeline] = useState(0);

    const changeExpandCompress = () => setIsExpand((prevState) => !prevState);
    const showSetting = () => setIsSetting((prevState) => !prevState);
    const pauseLayout = () => setIsPause((prevState) => !prevState);
    const nextLayout = () => {
        if (isPause) {
            setIsPause(false);
        }
        setLayoutIndex((prev) => 
        prev === pageTimeline ? 0 : ++prev);
    };
    const handleSubmitSetting = (data) => {
        if (data.line.ID !== line?.ID) {
            setLine(data.line);
        }
        setMachines(data.machines);
        if (data.interval !== intervalLayout) {
            setIntervalLayout(data.interval);
        }
        const totalPage = Math.ceil(machines.length / pageNumber) + 1;
        setPageTimeline(totalPage);
        setLayoutIndex(0);

        // save localStorage
        localStorage.setItem(
            localStorageKey,
            JSON.stringify({
                line: data.line,
                machines: data.machines,
                intervalLayout: data.interval,
            })
        );
    };

    const processDisplayTimeline = () => {
        if (!machines || !machines.length) return false;
        if (!machineTimelines.length) {
            const machineLocals = machines.slice(0, pageNumber);
            setMachineTimelines(machineLocals);
        } else {
            const machineLocals = machines.slice(
                (layoutIndex - 1) * pageNumber,
                layoutIndex * pageNumber
            );
            console.log("2:", machineLocals);
            setMachineTimelines(machineLocals);
        }
    };

    const handleInitData = async () => {
        const { success, data: resLine } = await getLines();
        if (success) {
            const lineFirstId = resLine.data[0]?.ID;
            setLines(resLine.data.map(({ ID, Name }) => ({ ID, Name })));
            setLine(resLine.data[0]);
            const { success, data: machines } = await getByLine(lineFirstId);
            if (success) {
                setMachines(machines.map(({ ID, Name }) => ({ ID, Name })));
            }
            const totalPage = Math.ceil(machines.length / pageNumber) + 1;
            setPageTimeline(totalPage);
            // save localStorage
            localStorage.setItem(
                localStorageKey,
                JSON.stringify({
                    line: resLine.data[0],
                    machines: machines,
                    intervalLayout,
                })
            );
        }
    };

    useEffect(() => {
        const intervalKey = setInterval(
            () => {
                if (!isPause) {
                    setLayoutIndex((prev) =>
                        prev === pageTimeline ? 0 : ++prev
                    );
                }
            },
            intervalLayout ? intervalLayout * 1000 : 5000
        );

        return () => {
            clearInterval(intervalKey);
        };
    }, [intervalLayout, isPause, pageTimeline]);

    useEffect(() => {
        if (layoutIndex > 0 && layoutIndex < pageTimeline) {
            processDisplayTimeline();
        }
    }, [layoutIndex]);

    useEffect(() => {
        // check data from localStorage
        const dataInMemory = localStorage.getItem(localStorageKey);
        if (dataInMemory) {
            const dataObject = JSON.parse(dataInMemory);
            const line = dataObject.line;
            const machines = dataObject.machines;
            const intervalLayout = dataObject.intervalLayout;
            setLine(line);
            setMachines(machines);
            const totalPage = Math.ceil(machines.length / pageNumber) + 1;
            setPageTimeline(totalPage);

            setIntervalLayout(intervalLayout);
        } else {
            handleInitData();
        }
    }, []);

    return (
        <VisualizationContext.Provider
            value={{
                isExpand,
                changeExpandCompress,
                pauseLayout,
                isSetting,
                showSetting,
                nextLayout,
                socket,
                line,
                lines,
                machines,
                layoutIndex,
                intervalLayout,
                handleSubmitSetting,
            }}
        >
            <div
                style={{
                    backgroundColor: "#000000",
                    position: isExpand ? "fixed" : "relative",
                    top: 0,
                    left: 0,
                    width: "100%",
                    height: isExpand ? "100vh" : "100%",
                    zIndex: isExpand ? 9999 : 1,
                    display: "flex",
                    flexDirection: "column",
                }}
            >
                <HeaderBar />
                <div
                    style={{
                        display: "flex",
                        flexDirection: "row",
                        height: "100%",
                    }}
                >
                    <div
                        style={{
                            padding: 10,
                            flex: 1,
                            overflow: "auto",
                        }}
                    >
                        {layoutIndex === 0 && (
                            <MachineCardLayout machines={machines} />
                        )}
                        {layoutIndex > 0 && layoutIndex < pageTimeline && (
                            <MachineChartLayout machines={machineTimelines} />
                        )}
                        {layoutIndex === pageTimeline && (
                            <DefectiveChartLayout />
                        )}
                        <OptionButton />
                    </div>
                    <Setting />
                </div>
            </div>
        </VisualizationContext.Provider>
    );
};

const root = createRoot(document.getElementById("app"));
root.render(<Visualization />);
