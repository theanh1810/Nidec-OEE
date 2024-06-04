const { DataTypes } = require('sequelize')
const { Db, getDateTimeFormat } = require('./Db')

const { BIGINT, STRING, DATE, BOOLEAN, INTEGER } = DataTypes

const Model = Db.define('MasterStatus', {
	ID: { type: BIGINT, primaryKey: true, autoIncrement: true },
	Name: { type: STRING },
	Symbols: { type: STRING },
	Master_Status_Type_ID: { type: INTEGER },
	User_Created: { type: BIGINT },
	Time_Created: { type: DATE, get() { return getDateTimeFormat.call(this, 'Time_Created') } },
	User_Updated: { type: BIGINT },
	Time_Updated: { type: DATE, get() { return getDateTimeFormat.call(this, 'Time_Updated') } },
	IsDelete: { type: BOOLEAN, allowNull: false },
}, {
	tableName: 'Master_Status',
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