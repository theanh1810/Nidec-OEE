const { DataTypes } = require('sequelize')
const { Db, getDateTimeFormat } = require('./Db')

const { BIGINT, STRING, FLOAT, DATE, BOOLEAN } = DataTypes

const Model = Db.define('MasterMachine', {
    ID:                { type: BIGINT, primaryKey: true, autoIncrement: true },
    Name:              { type: STRING, allowNull: false },
    Symbols:           { type: STRING, allowNull: false },
    Stock_Max:         { type: FLOAT },
    Stock_Min:         { type: FLOAT },
    Note:              { type: STRING },
    Group_Machine:     { type: STRING },
    User_Created:      { type: BIGINT },
    Time_Created:      { type: DATE, get() { return getDateTimeFormat.call(this, 'Time_Created') }},
    User_Updated:      { type: BIGINT },
    Time_Updated:      { type: DATE, get() { return getDateTimeFormat.call(this, 'Time_Updated') }},
    IsDelete:          { type: BOOLEAN, allowNull: false },
    MAC:               { type: STRING }
}, {
    tableName: 'Master_Machine',
    timestamps: true,
    createdAt: 'Time_Created',
    updatedAt: 'Time_Updated',
    hasTrigger: true
})

Model.addScope('paginate', (page, size) => ({
    limit: size,
    offset: (page - 1) * size
}))

module.exports = Model