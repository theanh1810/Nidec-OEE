module.exports = {
    index(req, res) {
        res.sendFile(process.cwd() + '/resources/views/monitor.html')
    },

    calculator(req, res) {
        res.sendFile(process.cwd() + '/resources/views/calculator.html')
    }
}