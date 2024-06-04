const masterShift = require('../../models/masterShift')

module.exports = {
    async index(req, res) {
        res.json(await masterShift().where('IsDelete', '=', 0).get())
    },
}