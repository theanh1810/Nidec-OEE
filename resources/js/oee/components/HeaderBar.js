import { useContext } from "react";
import t from "../../lang";
import VisualizationContext from "../context/VisualizationContext";
const moment = require("moment");

const HeaderBar = () => {
    const { layoutIndex, line } = useContext(VisualizationContext);

    return (
        <div
            style={{
                display: "flex",
                alignItems: "center",
                backgroundColor: "#2e3445",
                height: 36,
                justifyContent: "space-between",
            }}
        >
            <div
                style={{
                    display: "flex",
                    flexDirection: "row",
                    alignItems: "center",
                }}
            >
                {layoutIndex !== 2 && (
                    <div
                        style={{ color: "white" }}
                    >{t(`Monitor Line`)}</div>
                )}
                {layoutIndex === 2 && (
                    <div style={{ color: "white" }}>{t("Data analysis")}</div>
                )}
            </div>

            <div style={{ color: "white" }}>{`${moment().format(
                "HH:mm:ss"
            )} - ${moment().format("DD/MM/yyyy")}`}</div>
            <div
                style={{
                    display: "flex",
                    flexDirection: "row",
                    alignItems: "center",
                }}
            >
                {layoutIndex === 0 && (
                    <div
                        style={{
                            display: "flex",
                            flexDirection: "row",
                            alignItems: "center",
                        }}
                    >
                        <div className="machine-running"></div>
                        <div style={{ color: "white" }}>RUNNING</div>
                        <div className="machine-error"></div>
                        <div style={{ color: "white" }}>ERROR</div>
                        <div className="machine-stop"></div>
                        <div style={{ color: "white" }}>STOP</div>
                        <div className="machine-disconnected"></div>
                        <div style={{ color: "white" }}>Disconnected</div>
                        <div className="ver-line"></div>
                        <div style={{ color: "white" }}>P: PLAN A: ACTUAL</div>
                    </div>
                )}
                {layoutIndex === 1 && (
                    <div
                        style={{
                            display: "flex",
                            flexDirection: "row",
                            alignItems: "center",
                        }}
                    >
                        <div className="machine-running"></div>
                        <div style={{ color: "white" }}>RUNNING</div>
                        <div className="machine-error"></div>
                        <div style={{ color: "white" }}>ERROR</div>
                        <div className="machine-stop"></div>
                        <div style={{ color: "white" }}>STOP</div>
                        <div className="machine-disconnected"></div>
                        <div style={{ color: "white" }}>Disconnected</div>
                        <div className="ver-line"></div>
                        <div style={{ color: "white" }}>
                            T: TARGET P: PLAN A: ACTUAL
                        </div>
                    </div>
                )}
            </div>
        </div>
    );
};

export default HeaderBar;
