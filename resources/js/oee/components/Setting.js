import { useContext, useState, useEffect } from "react";
import VisualizationContext from "../context/VisualizationContext";
import { Button, Modal, Select, Checkbox, Divider, InputNumber } from "antd";
import useMachine from "../api/useMachine";
import useLine from "../api/useLine";
import t from "../../lang";

const CheckboxGroup = Checkbox.Group;
const { MIX_KEY_LOCAL_STORAGE } = process.env;

var localStorageKey = `visualization-settings-${MIX_KEY_LOCAL_STORAGE || ""}`;

const Setting = () => {
    const {
        isSetting,
        showSetting,
        lines,
        line,
        machines,
        intervalLayout,
        handleSubmitSetting,
    } = useContext(VisualizationContext);
    const { getByLine } = useMachine();
    const { getLines } = useLine();

    const [open, setOpen] = useState(isSetting);

    const [loading, setLoading] = useState(false);
    const [machineAll, setMachineAll] = useState([]);
    const [lineAll, setLineAll] = useState(lines);
    const [intervalSetting, setInterval] = useState(0);
    const [intervalMachineSetting, setIntervalMachine] = useState(0);
    const [intervalParetoSetting, setIntervalPareto] = useState(0);
    const [machineSetting, setMachineSetting] = useState([]);
    const [lineSetting, setLineSetting] = useState({});

    const indeterminate =
        machineSetting.length > 0 && machineSetting.length < machineAll.length;
    const checkAll = machineAll.length === machineSetting.length;

    const handleOk = () => {
        setLoading(true);
        handleSubmitSetting({
            machines: machineAll
                .filter((a) => machineSetting.indexOf(a.ID) >= 0)
                .map(({ ID, Name }) => ({ ID, Name })),
            line: lineSetting,
            interval: intervalSetting,
            intervalMachine: intervalMachineSetting,
            intervalPareto: intervalParetoSetting,
        });
        showSetting(isSetting);
    };

    const handleCancel = () => {
        showSetting(isSetting);
    };

    const handleChange = async (value) => {
        const line = lineAll.filter((a) => a.ID == value)?.shift();
        setLineSetting(line);
        setLoading(true);
        const { success, data: machines } = await getByLine(line.ID);
        if (success) {
            // set default checkbox
            const machineByLine = localStorage.getItem(`${localStorageKey}`);
            if (machineByLine) {
                const data = JSON.parse(machineByLine);
                if (data.machines != null && data.machines.length > 0) {
                    setMachineSetting(data.machines.map(({ ID }) => ID));

                    // concat all machines
                    let machineAll = data.machines.map(({ ID, Name }) => ({
                        ID,
                        Name,
                    }));

                    const listInsert = machines.filter(
                        (a) => !data.machines.some((b) => b.ID == a.ID)
                    );
                    listInsert.forEach((element) => {
                        machineAll.unshift({
                            ID: element.ID,
                            Name: element.Name,
                        });
                    });

                    setMachineAll(machineAll);
                } else {
                    setMachineSetting(machines.map(({ ID }) => ID));
                    localStorage.setItem(
                        `${localStorageKey}`,
                        JSON.stringify({ machines })
                    );
                    setMachineAll(
                        machines.map(({ ID, Name }) => ({ ID, Name }))
                    );
                }
            } else {
                setMachineSetting(machines.map(({ ID }) => ID));
                localStorage.setItem(
                    `${localStorageKey}`,
                    JSON.stringify({ machines })
                );
                setMachineAll(machines.map(({ ID, Name }) => ({ ID, Name })));
            }
            setLoading(false);
        }
    };

    const intervalChange = (value) => {
        setInterval(value);
    };

    const intervalMachineChange = (value) => {
        setIntervalMachine(value);
    };

    const intervalParetoChange = (value) => {
        setIntervalPareto(value);
    };

    const checkBoxChange = (value) => {
        const listCheckbox = machineAll.filter((a) => value.indexOf(a.ID) >= 0);
        const machineByLine = localStorage.getItem(`${localStorageKey}`);
        if (machineByLine) {
            let data = JSON.parse(machineByLine);
            if (data?.machines?.length > 0) {
                data.machines = listCheckbox;
                localStorage.setItem(
                    `${localStorageKey}`,
                    JSON.stringify(data.machines)
                );
                setMachineSetting(value);
            } else {
                localStorage.setItem(
                    `${localStorageKey}`,
                    JSON.stringify({ machines: listCheckbox })
                );
                setMachineSetting(value);
            }
        } else {
            localStorage.setItem(
                `${localStorageKey}`,
                JSON.stringify({ machines: listCheckbox })
            );
            setMachineSetting(value);
        }
    };

    const onCheckAllChange = (e) => {
        setMachineSetting(e.target.checked ? machineAll?.map((a) => a.ID) : []);
        localStorage.setItem(`${localStorageKey}`, "");
        localStorage.setItem(`${localStorageKey}`, JSON.stringify(machineAll));
    };

    const handleInitData = async () => {
        if (line && line?.ID > 0) {
            setLineSetting(line);
            setLoading(true);
            const { success, data: machines } = await getByLine(line.ID);
            if (success) {
                const machineByLine = localStorage.getItem(
                    `${localStorageKey}`
                );
                if (machineByLine) {
                    const data = JSON.parse(machineByLine);
                    if (data.machines != null && data.machines.length > 0) {
                        setMachineSetting(data.machines.map(({ ID }) => ID));

                        // concat all machines
                        let machineAll = data.machines.map(({ ID, Name }) => ({
                            ID,
                            Name,
                        }));

                        const listInsert = machines.filter(
                            (a) => !data.machines.some((b) => b.ID == a.ID)
                        );
                        listInsert.forEach((element) => {
                            machineAll.unshift({
                                ID: element.ID,
                                Name: element.Name,
                            });
                        });

                        setMachineAll(machineAll);
                    } else {
                        setMachineSetting(machines.map(({ ID }) => ID));
                        localStorage.setItem(
                            `${localStorageKey}`,
                            JSON.stringify({ machines })
                        );
                        setMachineAll(
                            machines.map(({ ID, Name }) => ({ ID, Name }))
                        );
                    }
                } else {
                    setMachineSetting(machines.map(({ ID }) => ID));
                    localStorage.setItem(
                        `${localStorageKey}`,
                        JSON.stringify({ machines })
                    );
                    setMachineAll(
                        machines.map(({ ID, Name }) => ({ ID, Name }))
                    );
                }
                setLoading(false);
            }
        }
        if (!lines || lines.length == 0) {
            const { success, data: resLine } = await getLines();
            if (success) {
                setLineAll(resLine.data.map(({ ID, Name }) => ({ ID, Name })));
            }
        }
    };

    useEffect(() => {
        setOpen(isSetting);
        setInterval(intervalLayout);
        if (machines) {
            setMachineSetting(machines.map(({ ID }) => ID));
        }
        handleInitData();
    }, [isSetting, line]);

    return (
        <Modal
            open={open}
            title={t("Setting")}
            onOk={handleOk}
            onCancel={handleCancel}
            footer={[
                <Button
                    key="submit"
                    type="primary"
                    loading={loading}
                    onClick={handleOk}
                >
                    {t("Submit")}
                </Button>,
            ]}
        >
            <Select
                defaultValue={line?.ID}
                style={{ width: 120 }}
                onChange={handleChange}
                options={lineAll.map(({ ID, Name }) => ({
                    value: ID,
                    label: Name,
                }))}
            />
            {/* <Checkbox.Group
                style={{ width: "100%" }}
                options={machineAll.map(({ ID, Name }) => ({
                    value: ID,
                    label: Name,
                }))}
                defaultValue={machineSetting}
                onChange={checkBoxChange}
            ></Checkbox.Group> */}
            <Checkbox
                indeterminate={indeterminate}
                onChange={onCheckAllChange}
                checked={checkAll}
            >
                {t("Check all")}
            </Checkbox>
            <Divider />
            <CheckboxGroup
                options={machineAll.map(({ ID, Name }) => ({
                    value: ID,
                    label: Name,
                }))}
                value={machineSetting}
                onChange={checkBoxChange}
            />
            <Divider />

            <div
                style={{
                    display: "flex",
                    flexDirection: "column",
                }}
            >
                <div
                    style={{
                        display: "flex",
                        flexDirection: "row",
                        justifyContent: "space-between",
                        alignItems: "center",
                    }}
                >
                    <div>{`${t("Screen change time")}:`}</div>
                    <InputNumber
                        style={{ width: 100 }}
                        value={intervalSetting}
                        onChange={intervalChange}
                        min={1}
                    />
                </div>
            </div>
        </Modal>
    );
};

export default Setting;
