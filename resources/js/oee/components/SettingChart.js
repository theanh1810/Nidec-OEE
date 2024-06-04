import { useContext, useState, useEffect } from "react";
import ChartContext from "../context/ChartContext";
import { Button, Modal, Checkbox, Divider } from "antd";
import useMachine from "../api/useMachine";
import t from "../../lang";

const CheckboxGroup = Checkbox.Group;

const { MIX_KEY_LOCAL_STORAGE } = process.env;
var lineMachineChartStorageKey = `setting-chart-${MIX_KEY_LOCAL_STORAGE || ''}`;

const SettingChart = () => {
    const {
        isSettingChart,
        selectedLine,
        machines,
        handleSubmitSettingChart,
        showSettingChart
    } = useContext(ChartContext);
    const { getByLine } = useMachine();
    const [open, setOpen] = useState(isSettingChart);

    const [machineAll, setMachineAll] = useState([]);
    const [machineSetting, setMachineSetting] = useState([]);

    const indeterminate =
        machineSetting.length > 0 && machineSetting.length < machineAll.length;
    const checkAll = machineAll.length === machineSetting.length;

    const handleOk = () => {
        const machineLocals = machineAll
        .filter((a) => machineSetting.indexOf(a.ID) >= 0)
        .map(({ ID, Name }) => ({ ID, Name }));
        localStorage.setItem(
            `${lineMachineChartStorageKey}-${selectedLine}`,
            machineLocals
        );
        handleSubmitSettingChart({
            machines:machineLocals
        });
        showSettingChart(isSettingChart);
    };

    const handleCancel = () => {
        showSettingChart(isSettingChart);
    };

    const checkBoxChange = (value) => {
        setMachineSetting(value);
    };

    const onCheckAllChange = (e) => {
        setMachineSetting(e.target.checked ? machineAll?.map((a) => a.ID) : []);
    };

    const handleInitData = async () => {
        if (selectedLine) {
            const { success, data: machines } = await getByLine(selectedLine);
            if (success) {
                setMachineAll(machines.map(({ ID, Name }) => ({ ID, Name })));
                // set default checkbox
                const machineByLine = localStorage.getItem(
                    `${lineMachineChartStorageKey}-${selectedLine}`
                );
                if (machineByLine) {
                    const listMachines = JSON.parse(machineByLine);
                    setMachineSetting(listMachines.map(({ ID }) => ID));
                } else {
                    setMachineSetting(machines.map(({ ID }) => ID));
                    localStorage.setItem(
                        `${lineMachineChartStorageKey}-${selectedLine}`,
                        JSON.stringify(machines)
                    );
                }
            }
        }
    };

    useEffect(() => {
        setOpen(isSettingChart);
        setMachineSetting(machines.map(({ ID }) => ID));
        handleInitData();
    }, [isSettingChart, selectedLine]);

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
                    onClick={handleOk}
                >
                    {t("Submit")}
                </Button>,
            ]}
        >
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
        </Modal>
    );
};

export default SettingChart;
