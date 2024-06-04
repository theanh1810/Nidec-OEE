<?php

namespace App\Models\MasterData;

use Illuminate\Database\Eloquent\Model;
use DateTimeInterface;

class MasterMachine extends Model
{
	const CREATED_AT = 'Time_Created';
	const UPDATED_AT = 'Time_Updated';

	protected $connection = 'sqlsrv';
	protected $table = 'Master_Machine';
	protected $primaryKey = 'ID';
	protected $fillable = [
		'Name',
		'Symbols',
		'MAC',
		'Stock_Min',
		'Stock_Max',
		'Note',
		'Line_ID',
		'Time_Created',
		'User_Created',
		'Time_Updated',
		'User_Updated',
		'IsDelete'
	];

	protected function serializeDate(DateTimeInterface $date)
	{
		return $date->format('Y-m-d H:i:s');
	}

	public function user_created()
	{
		return $this->hasOne('App\Models\User', 'id', 'User_Created')->whereIsdelete(0);
	}

	public function user_updated()
	{
		return $this->hasOne('App\Models\User', 'id', 'User_Updated')->whereIsdelete(0);
	}

	public function running()
	{
		return $this->hasOne('App\Models\ProductionPlan\CommandProductionDetail', 'Part_Action', 'ID')->where('Status', '=', 1);
	}

    public function line()
    {
      return $this->hasOne('App\Models\MasterData\MasterLine', 'ID', 'Line_ID')->whereIsdelete(0);
    }

}
