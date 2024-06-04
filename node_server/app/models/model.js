const moment = require("moment");
const sql = require("mssql");
const {
    SQLSRV_HOST,
    SQLSRV_PORT,
    SQLSRV_DATABASE,
    SQLSRV_USERNAME,
    SQLSRV_PASSWORD,
} = process.env;
const config = {
    user: SQLSRV_USERNAME,
    password: SQLSRV_PASSWORD,
    database: SQLSRV_DATABASE,
    server: SQLSRV_HOST,
    pool: {
        max: 200,
        min: 10,
        idleTimeoutMillis: 10000,
    },
    options: {
        encrypt: false,
        trustServerCertificate: true,
        useUTC: false,
    },
};
const connectionPool = new sql.ConnectionPool(config);
let pool = null;

const sqlConnect = () =>
    new Promise((resolve, reject) => {
        connectionPool
            .connect()
            .then((_pool) => {
                pool = _pool;
                console.log("created sql connection pool");
                resolve();
            })
            .catch((err) => reject(err));
    });

const getTransaction = () => pool.transaction();

const model = (table) => {
    const params = {
        top: 0,
        select: [],
        where: [],
        insert: {
            Time_Created: moment().format("YYYY-MM-DD HH:mm:ss").toString(),
            Time_Updated: moment().format("YYYY-MM-DD HH:mm:ss").toString(),
        },
        update: {
            Time_Updated: moment().format("YYYY-MM-DD HH:mm:ss").toString(),
        },
    };

    const parseQuery = (type) => {
        const where = params.where.length
            ? `WHERE ${params.where.join(" ")}`
            : "";

        switch (type) {
            case "get": {
                const top = params.top ? `TOP(${params.top})` : "";
                const select = params.select.length
                    ? params.select.join(",")
                    : "*";
                return `SELECT ${top} ${select} FROM ${table} ${where}`;
            }
            case "insert": {
                const columns = Object.keys(params.insert).join(",");
                const values = Object.values(params.insert)
                    .map((value) => (value === null ? "null" : `'${value}'`))
                    .join(",");
                return `INSERT INTO ${table} (${columns}) VALUES (${values}); SELECT SCOPE_IDENTITY() AS ID`;
            }
            case "update": {
                const data = Object.entries(params.update)
                    .map(
                        ([key, value]) =>
                            `${key} = ${value === null ? "null" : `'${value}'`}`
                    )
                    .join(",");
                return `UPDATE ${table} SET ${data} ${where}`;
            }
        }
    };

    const query = async (type) => {
        const q = parseQuery(type);
        // console.log("query:", q);
        try {
            return await pool.query(q);
        } catch (err) {
            console.log("query err:", err);

            throw q;
        }
    };

    return {
        async get(fields) {
            if (fields) {
                if (fields.constructor !== [].constructor)
                    throw new Error("fields must be an array");
                params.select = fields;
            }
            const { recordset } = await query("get");
            return recordset;
        },
        async first() {
            params.top = 1;
            const { recordset } = await query("get");
            return recordset[0];
        },
        async find(id) {
            if (typeof id === "undefined")
                throw new Error("id can not undefined");
            params.top = 1;
            params.where.push(`ID = '${id}'`);
            const { recordset } = await query("get");
            return recordset[0];
        },
        async insert(data) {
            if (data.constructor !== {}.constructor)
                throw new Error("params must be a json");
            params.insert = {
                ...params.insert,
                ...data,
            };
            const { recordset } = await query("insert");
            return recordset[0].ID;
        },
        async update(data) {
            if (data.constructor !== {}.constructor)
                throw new Error("params must be a json");
            params.update = {
                ...params.update,
                ...data,
            };
            const { rowsAffected } = await query("update");
            return rowsAffected[0] && rowsAffected[1];
        },
        where(field, comparison, value) {
            if (typeof field === "undefined")
                throw new Error("field can not undefined");
            if (typeof comparison === "undefined")
                throw new Error("comparison can not undefined");
            if (typeof value === "undefined")
                throw new Error("value can not undefined");
            params.where.length
                ? params.where.push(`AND ${field} ${comparison} '${value}'`)
                : params.where.push(`${field} ${comparison} '${value}'`);
            return {
                where: this.where,
                orWhere: this.orWhere,
                get: this.get,
                first: this.first,
                update: this.update,
            };
        },
        orWhere(field, comparison, value) {
            if (typeof field === "undefined")
                throw new Error("field can not undefined");
            if (typeof comparison === "undefined")
                throw new Error("comparison can not undefined");
            if (typeof value === "undefined")
                throw new Error("value can not undefined");
            params.where.length
                ? params.where.push(`OR ${field} ${comparison} '${value}'`)
                : params.where.push(`${field} ${comparison} '${value}'`);
            return {
                where: this.where,
                orWhere: this.orWhere,
                get: this.get,
                first: this.first,
                update: this.update,
            };
        },
    };
};

module.exports = { sqlConnect, model, getTransaction };
