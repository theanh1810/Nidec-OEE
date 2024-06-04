const { io } = require('../../../server/io')
const { getTransaction } = require('../../models/model')
const masterMachine = require('../../models/masterMachine')
const changeShiftService = require('../../services/changeShiftService')

module.exports = {
    async index(req, res) {
        res.sendFile(`${process.cwd()}/resources/views/index.html`)
    },

    sendMessage(req, res) {
        const { query } = req
        io.emit('chat message', query.message)
        // io.emit('machine-1-status', {
        //     id: 1,
        //     status: '1'
        // })
        // io.emit('machine-1', {
        //     shift: {
        //         oee: 100,
        //         a: Math.random() * 100,
        //         p: Math.random() * 100,
        //         q: Math.random() * 100
        //     },
        //     day: {
        //         oee: 80,
        //         a: Math.random() * 100,
        //         p: Math.random() * 100,
        //         q: Math.random() * 100
        //     },
        //     production: {
        //         product: 'ABD',
        //         plan: 500,
        //         actual: 45
        //     }
        // })
        res.json(query.message)
    },

    async model(req, res) {
        const transaction = getTransaction()
        try {
            await transaction.begin()
            // const machine1 = await masterMachine().insert({
            //     Name: null,
            //     Symbols: "orm test insert machine",
            //     Stock_Max: 100,
            //     Stock_Min: 56,
            //     Note: null,
            //     IsDelete: false,
            //     MAC: "89-88A-BC"
            // })
            const machine2 = await masterMachine().insert({
                Name: "transaction 2",
                Symbols: "orm test transaction",
                Stock_Max: 100,
                Stock_Min: 56,
                Note: null,
                IsDelete: false,
                MAC: "89-88A-BC"
            })
            await transaction.commit()
            // res.json({ machine1, machine2 })
            // const update = await masterMachine().where('ID', '=', 102).update({
            //     Name: 'trung'
            // })
            // res.json(update)
            res.json(await masterMachine().get())
        } catch(err) {
            await transaction.rollback()
            res.status(500).json(err.stack)
        }
    },

    getShift(req, res) {
        res.json(changeShiftService.getCurrentShift())
    },

    async reset(req, res) {
        res.json(await changeShiftService.reset())
    }
}