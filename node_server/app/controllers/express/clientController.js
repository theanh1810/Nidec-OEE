const { getClients } = require('../../../server/socket')

module.exports = {
    index(req, res) {
        const clients = getClients();
        let clientsConvert = {};
         // sent to gateway server
        Object.keys(clients).forEach(a=> {
            clientsConvert[a] =  { address:clients[a].address,
                 origin: clients[a].origin, userAgent:clients[a].userAgent, id:a };
        })
        res.json(clientsConvert)
    }
}
