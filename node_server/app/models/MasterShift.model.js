const { DataTypes } = require('sequelize')
const { Db, getDateTimeFormat } = require('./Db')

const { BIGINT, STRING, DATE, BOOLEAN, INTEGER } = DataTypes

const Model = Db.define('MasterShift', {
	ID: { type: BIGINT, primaryKey: true, autoIncrement: true },
	Name: { type: STRING },
	Rested: { type: STRING },
	Start_Time: { type: STRING },
	End_Time: { type: STRING },
	Shift: { type: INTEGER }, 
	Group_Machine: { type: STRING },
	User_Created: { type: BIGINT },
	Time_Created: { type: DATE, get() { return getDateTimeFormat.call(this, 'Time_Created') } },
	User_Updated: { type: BIGINT },
	Time_Updated: { type: DATE, get() { return getDateTimeFormat.call(this, 'Time_Updated') } },
	IsDelete: { type: BOOLEAN, allowNull: false }
}, {
	tableName: 'Master_Shift',
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