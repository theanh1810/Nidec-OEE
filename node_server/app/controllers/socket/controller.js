const machineLoopService = require("../../services/machineLoopService");

module.exports = {
    statusIot({ io, payload }) {
        console.log({ statusIot: JSON.stringify(payload) });
        const { machineId, status, statusMachine } = payload;

        if (Number(status)) {
            machineLoopService.addOrUpdateMachine(
                machineId,
                Number(statusMachine)
            ); // 1: run | 2: stop not error | 3: stop due to error
        } else {
            machineLoopService.deleteMachine(machineId);
        }

        io.emit(`machine-${machineId}`, {
            iot: status,
            machineStatus: statusMachine,
        });
    },

    statusMachine({ io, payload }) {
        console.log({ statusMachine: JSON.stringify(payload) });
        const { run, stop, error, machineId } = payload;
        let machineStatus = 4;

        if (Boolean(run)) {
            machineStatus = 1;
        } else if (Boolean(stop)) {
            machineStatus = 2;
        } else if (Boolean(error)) {
            machineStatus = 3;
        } else {
            machineStatus = 4;
        }

        machineLoopService.addOrUpdateMachine(machineId, machineStatus);

        io.emit(`machine-${machineId}`, {
            machineStatus,
        });
    },

    callStatus({ io, payload }) {
        console.log({ callStatus: JSON.stringify(payload) });

        for (const { run, stop, error, machineId } of payload) {
            let machineStatus = 4;

            if (Number(run)) {
                machineStatus = 1;
            } else if (Number(stop)) {
                machineStatus = 2;
            } else if (Number(error)) {
                machineStatus = 3;
            } else {
                machineStatus = 4;
            }

            machineLoopService.addOrUpdateMachine(machineId, machineStatus);

            io.emit(`machine-${machineId}`, {
                machineStatus,
            });
        }
    },
};
