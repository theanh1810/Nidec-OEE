const { DataTypes } = require('sequelize')
const { Db } = require('./Db')

const { BIGINT, STRING } = DataTypes

const Model = Db.define('MasterStatusType', {
	ID: { type: BIGINT, primaryKey: true, autoIncrement: true },
	Name: { type: STRING },
}, {
	tableName: 'Master_Status_Type',
	timestamps: false
})

module.exports = Model