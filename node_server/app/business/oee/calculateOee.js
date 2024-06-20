const caculateOee = function() {
    let a, p, q, oee

    const calculateA = () => {
        a = this.plannedTime ? (this.fullRunTime / this.plannedTime) : 0;
    }

    const calculateP = () => {
        p = this.runtime ? (this.netRuntime / this.runtime) : 0;
    }

    const calculateQ = () => {
        q = this.total ? ((this.total - this.ng) / this.total) : 0;
    }

    const calculateOEE = () => {
        oee = Math.floor((a * p * q)*10000)/100;
    }

    calculateA()
    calculateP()
    calculateQ()
    calculateOEE()

    return {
        Oee: oee,
        A: Math.floor(a * 10000) / 100,
        P: ((Math.floor(p * 10000) / 100) > 100) ? 100 : Math.floor(p * 10000) / 100,
        Q: Math.floor(q * 10000) / 100,
    }
}

module.exports = caculateOee
